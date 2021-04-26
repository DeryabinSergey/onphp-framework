<?php
/***************************************************************************
 *   Copyright (C) 2011 by Sergey S. Sergeev                               *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 ***************************************************************************/

namespace OnPHP\Main\Util\AMQP\Pecl;

use AMQPChannel;
use AMQPConnectionException;
use AMQPExchange;
use AMQPExchangeException;
use AMQPQueue;
use AMQPQueueException;
use Exception;
use OnPHP\Core\Base\Assert;
use OnPHP\Core\Exception\ObjectNotFoundException;
use OnPHP\Core\Exception\UnimplementedFeatureException;
use OnPHP\Core\Exception\WrongArgumentException;
use OnPHP\Core\Exception\WrongStateException;
use OnPHP\Main\Util\AMQP\AMQPBaseChannel;
use OnPHP\Main\Util\AMQP\AMQPChannelInterface;
use OnPHP\Main\Util\AMQP\AMQPConsumer;
use OnPHP\Main\Util\AMQP\AMQPExchangeConfig;
use OnPHP\Main\Util\AMQP\AMQPExchangeType;
use OnPHP\Main\Util\AMQP\AMQPIncomingMessage;
use OnPHP\Main\Util\AMQP\AMQPOutgoingMessage;
use OnPHP\Main\Util\AMQP\AMQPQueueConfig;
use OnPHP\Main\Util\AMQP\Exception\AMQPServerConnectionException;
use OnPHP\Main\Util\AMQP\Exception\AMQPServerException;
use Throwable;

final class AMQPPeclChannel extends AMQPBaseChannel
{
	const NIL = 'nil';
	const AMQP_NONE = AMQP_NOPARAM;
    /**
     * @var array
     */
	protected array $exchangeList = array();
    /**
     * @var array
     */
	protected array $queueList = array();
    /**
     * @var bool
     */
	protected bool $opened = false;
    /**
     * @var AMQPChannel|null
     */
	protected ?AMQPChannel $link = null;

	/**
	 * @var AMQPConsumer
	**/
	protected $consumer = null;

    /**
     * @return bool
     */
	public function isOpen(): bool
	{
		return $this->opened === true;
	}

    /**
     * @return static
     */
	public function open(): AMQPPeclChannel
	{
		$this->opened = true;

		return $this;
	}

    /**
     * @return static
     */
	public function close(): AMQPPeclChannel
	{
		$this->opened = false;

		return $this;
	}

    /**
     * @param string $deliveryTag
     * @param bool $multiple
     * @return static
     * @throws WrongArgumentException
     * @throws AMQPServerConnectionException
     * @throws AMQPServerException
     */
	public function basicAck(string $deliveryTag, bool $multiple = false): AMQPPeclChannel
	{
		try {
			$obj = $this->lookupQueue(self::NIL);
			$result = $obj->ack(
				$deliveryTag,
				$multiple === true
					? AMQP_MULTIPLE
					: self::AMQP_NONE
			);
		} catch (Throwable $e) {
			$this->clearConnection();

			throw new AMQPServerException(
				$e->getMessage(),
				$e->getCode(),
				$e
			);
		}

		return $this
            ->checkCommandResult($result, "Could not ack message");
	}

    /**
     * @param string $consumerTag
     * @return static
     * @throws WrongArgumentException
     * @throws WrongStateException
     * @throws AMQPServerConnectionException
     * @throws AMQPServerException
     */
	public function basicCancel(string $consumerTag): AMQPPeclChannel
	{
		if (!$this->consumer instanceof AMQPConsumer) {
            throw new WrongStateException();
        }

		try {
			$obj = $this->lookupQueue($consumerTag);

			$result = $obj->cancel($consumerTag);
		} catch (Throwable $e) {
			$this->clearConnection();

			throw new AMQPServerException(
				$e->getMessage(),
				$e->getCode(),
				$e
			);
		}

		return $this
            ->checkCommandResult($result, "Could not cancel queue");
	}

    /**
     * @param string $queue
     * @param bool $autoAck
     * @param AMQPConsumer $callback
     * @return static
     * @throws WrongArgumentException
     * @throws AMQPServerException
     */
	public function basicConsume(string $queue, bool $autoAck, AMQPConsumer $callback): AMQPPeclChannel
	{
		Assert::isInstance($callback, AMQPPeclQueueConsumer::class);

		try {
			$this->consumer = $callback->
				setQueueName($queue)->
				setAutoAcknowledge($autoAck === true);

			$obj = $this->lookupQueue($queue);

			$this->consumer->handleConsumeOk(
				$this->consumer->getConsumerTag()
			);

			/**
			 * blocking function
			 */
			$obj->consume(
				array($callback, 'handlePeclDelivery'),
				$autoAck
					? AMQP_AUTOACK
					: self::AMQP_NONE
			);
		} catch (Throwable $e) {
			$this->clearConnection();

			throw new AMQPServerException(
				$e->getMessage(),
				$e->getCode(),
				$e
			);
		}

		return $this;
	}

    /**
     * @param string $queue
     * @param bool $autoAck
     * @return AMQPIncomingMessage
     * @throws ObjectNotFoundException
     * @throws WrongArgumentException
     * @throws AMQPServerException
     */
	public function basicGet(string $queue, bool $autoAck = true): AMQPIncomingMessage
	{
		try {
			$obj = $this->lookupQueue($queue);
			$message = $obj->get(
				($autoAck === true)
					? AMQP_AUTOACK
					: self::AMQP_NONE
			);
		} catch (Throwable $e) {
			$this->clearConnection();

			throw new AMQPServerException(
				$e->getMessage(),
				$e->getCode(),
				$e
			);
		}

		if (!$message) {
            throw new ObjectNotFoundException("AMQP queue with name '{$queue}' is empty");
        }

		return AMQPPeclIncomingMessageAdapter::convert($message);
	}

    /**
     * @param string $exchange
     * @param string $routingKey
     * @param AMQPOutgoingMessage $msg
     * @return static
     * @throws WrongArgumentException
     * @throws AMQPServerConnectionException
     * @throws AMQPServerException
     */
	public function basicPublish(string $exchange, string $routingKey, AMQPOutgoingMessage $msg): AMQPPeclChannel
    {
		try {
			$obj = $this->lookupExchange($exchange);

			$result = $obj->publish(
				$msg->getBody(),
				$routingKey,
				$msg->getBitmask(new AMQPPeclOutgoingMessageBitmask()),
				$msg->getProperties()
			);
		} catch (Throwable $e) {
			$this->clearConnection();

			throw new AMQPServerException(
				$e->getMessage(),
				$e->getCode(),
				$e
			);
		}

		return $this
            ->checkCommandResult($result, "Could not publish to exchange");
	}

    /**
     * @param int $prefetchSize
     * @param int $prefetchCount
     * @return static
     * @throws WrongArgumentException
     * @throws AMQPServerConnectionException
     * @throws AMQPServerException
     */
	public function basicQos(int $prefetchSize, int $prefetchCount): AMQPPeclChannel
	{
		try {
			$result = $this->getChannelLink()->qos(
				$prefetchSize,
				$prefetchCount
			);
		} catch (Throwable $e) {
			$this->clearConnection();

			throw new AMQPServerException(
				$e->getMessage(),
				$e->getCode(),
				$e
			);
		}

		return $this
            ->checkCommandResult($result, "Could not publish to exchange");
	}

    /**
     * @param string $destinationName
     * @param string $sourceName
     * @param string $routingKey
     * @return static
     * @throws WrongArgumentException
     * @throws AMQPServerConnectionException
     * @throws AMQPServerException
     */
	public function exchangeBind(string $destinationName, string $sourceName, string $routingKey): AMQPPeclChannel
	{
		try {
			$obj = $this->lookupExchange($destinationName);

			$result = $obj->bind(
				$sourceName,
				$routingKey
			);
		} catch (Throwable $e) {
			$this->clearConnection();

			throw new AMQPServerException(
				$e->getMessage(),
				$e->getCode(),
				$e
			);
		}

		return $this
            ->checkCommandResult($result, "Could not bind exchange");
	}

    /**
     * @param string $destinationName
     * @param string $sourceName
     * @param string $routingKey
     * @return AMQPPeclChannel
     * @throws UnimplementedFeatureException
     */
	public function exchangeUnbind(string $destinationName, string $sourceName, string $routingKey): AMQPPeclChannel
	{
		throw new UnimplementedFeatureException();
	}

    /**
     * @param string $name
     * @param AMQPExchangeConfig $conf
     * @return static
     * @throws WrongArgumentException
     * @throws AMQPServerConnectionException
     * @throws AMQPServerException
     */
	public function exchangeDeclare(string $name, AMQPExchangeConfig $conf): AMQPPeclChannel
	{
		$this->checkConnection();

		if (!$conf->getType() instanceof AMQPExchangeType) {
            throw new WrongArgumentException(
                "AMQP exchange type is not set"
            );
        }

		try {
			$this->exchangeList[$name] =
				new AMQPExchange($this->getChannelLink());

			$obj = $this->exchangeList[$name];

			$obj->setName($name);
			$obj->setType($conf->getType()->getName());
			$obj->setFlags(
				$conf->getBitmask(new AMQPPeclExchangeBitmask())
			);
			$obj->setArguments($conf->getArguments());

			$result = $obj->declareExchange();
		} catch (Throwable $e) {
			$this->clearConnection();

			throw new AMQPServerException(
				$e->getMessage(),
				$e->getCode(),
				$e
			);
		}

		return  $this
            ->checkCommandResult($result, "Could not declare exchange");
	}

    /**
     * @param string $name
     * @param bool $ifUnused
     * @return static
     * @throws WrongArgumentException
     * @throws AMQPServerConnectionException
     * @throws AMQPServerException
     */
	public function exchangeDelete(string $name, bool $ifUnused = false): AMQPPeclChannel
    {
		$bitmask = self::AMQP_NONE;

		if ($ifUnused) {
            $bitmask = $bitmask | AMQP_IFUNUSED;
        }

		try {
			$obj = $this->lookupExchange($name);
			$result = $obj->delete($name, $bitmask);
		} catch (Throwable $e) {
			$this->clearConnection();

			throw new AMQPServerException(
				$e->getMessage(),
				$e->getCode(),
				$e
			);
		}

		return $this
            ->checkCommandResult($result, "Could not delete exchange")
            ->unsetExchange($name);
	}

    /**
     * @param string $name
     * @param string $exchange
     * @param string $routingKey
     * @return static
     * @throws WrongArgumentException
     * @throws AMQPServerConnectionException
     * @throws AMQPServerException
     */
	public function queueBind(string $name, string $exchange, string $routingKey): AMQPPeclChannel
	{
		try {
			$obj = $this->lookupQueue($name);
			$result = $obj->bind($exchange, $routingKey);
		} catch (Throwable $e) {
			$this->clearConnection();

			throw new AMQPServerException(
				$e->getMessage(),
				$e->getCode(),
				$e
			);
		}

		return $this
            ->checkCommandResult($result, "Could not bind queue");
	}

    /**
     * @param string $name
     * @param AMQPQueueConfig $conf
     * @return int
     * @throws WrongArgumentException
     * @throws AMQPServerConnectionException
     * @throws AMQPServerException
     */
	public function queueDeclare(string $name, AMQPQueueConfig $conf): int
	{
		$this->checkConnection();

		try {
			if (isset($this->queueList[$name])) {
                unset($this->queueList[$name]);
            }

			$this->queueList[$name] =
				new AMQPQueue($this->getChannelLink());

			$obj = $this->queueList[$name];
			$obj->setName($name);
			$obj->setFlags(
				$conf->getBitmask(new AMQPPeclQueueBitmask())
			);
			$obj->setArguments($conf->getArguments());

			$result = $obj->declareQueue();
		} catch (Throwable $e) {
			$this->clearConnection();

			throw new AMQPServerException(
				$e->getMessage(),
				$e->getCode(),
				$e
			);
		}

		$this->checkCommandResult(is_int($result),"Could not declare queue");

		return $result;
	}

    /**
     * @param string $name
     * @return static
     * @throws WrongArgumentException
     * @throws AMQPServerConnectionException
     * @throws AMQPServerException
     */
	public function queueDelete(string $name): AMQPPeclChannel
	{
		try {
			$obj = $this->lookupQueue($name);
			$result = $obj->delete();
		} catch (Throwable $e) {
			$this->clearConnection();

			throw new AMQPServerException(
				$e->getMessage(),
				$e->getCode(),
				$e
			);
		}

		return $this
            ->checkCommandResult($result, "Could not delete queue")
            ->unsetQueue($name);
	}

    /**
     * @param string $name
     * @return static
     * @throws WrongArgumentException
     * @throws AMQPServerConnectionException
     * @throws AMQPServerException
     */
	public function queuePurge(string $name): AMQPPeclChannel
	{
		try {
			$obj = $this->lookupQueue($name);
			$result = $obj->purge();
		} catch (Throwable $e) {
			$this->clearConnection();

			throw new AMQPServerException(
				$e->getMessage(),
				$e->getCode(),
				$e
			);
		}

		return $this
            ->checkCommandResult($result, "Could not purge queue");
	}

    /**
     * @param string $name
     * @param string $exchange
     * @param string $routingKey
     * @return static
     * @throws WrongArgumentException
     * @throws AMQPServerConnectionException
     * @throws AMQPServerException
     */
	public function queueUnbind(string $name, string $exchange, string $routingKey): AMQPPeclChannel
	{
		try {
			$obj = $this->lookupQueue($name);
			$result = $obj->unbind($exchange, $routingKey);
		} catch (Throwable $e) {
			$this->clearConnection();

			throw new AMQPServerException(
				$e->getMessage(),
				$e->getCode(),
				$e
			);
		}

		return $this
            ->checkCommandResult($result, "Could not unbind queue");
	}

    /**
     * @param string $name
     * @return AMQPExchange
     * @throws AMQPConnectionException
     * @throws AMQPExchangeException
     * @throws WrongArgumentException
     */
	protected function lookupExchange(string $name): AMQPExchange
	{
		$this->checkConnection();

		if (!isset($this->exchangeList[$name])) {
			$this->exchangeList[$name] =
				new AMQPExchange($this->getChannelLink());
			$this->exchangeList[$name]->setName($name);
		}

		return $this->exchangeList[$name];
	}

    /**
     * @param string $name
     * @return static
     */
	protected function unsetExchange(string $name): AMQPPeclChannel
	{
		if (isset($this->exchangeList[$name])) {
            unset($this->exchangeList[$name]);
        }

		return $this;
	}

    /**
     * @param string $name
     * @return AMQPQueue
     * @throws AMQPConnectionException
     * @throws AMQPQueueException
     * @throws WrongArgumentException
     */
	protected function lookupQueue(string $name): AMQPQueue
	{
		$this->checkConnection();

		if (!isset($this->queueList[$name])) {
			$this->queueList[$name] = new AMQPQueue($this->getChannelLink());
			if ($name != self::NIL) {
                $this->queueList[$name]->setName($name);
            }
		}

		return $this->queueList[$name];
	}

    /**
     * @param string $name
     * @return static
     */
	protected function unsetQueue(string $name): AMQPPeclChannel
	{
		if (isset($this->queueList[$name])) {
            unset($this->queueList[$name]);
        }

		return $this;
	}

    /**
     * @param bool $boolean
     * @param string $message
     * @return static
     * @throws WrongArgumentException
     * @throws AMQPServerConnectionException
     */
	protected function checkCommandResult(bool $boolean, string $message): AMQPPeclChannel
	{
		if ($boolean !== true) {
			//link is not alive!!!
			$this->transport->getLink()->disconnect();
			throw new AMQPServerConnectionException($message);
		}

		return $this;
	}

    /**
     * @return static
     */
	protected function clearConnection(): AMQPPeclChannel
	{
		unset($this->link);
		$this->link = null;

		$this->exchangeList = array();
		$this->queueList = array();

		return $this;
	}

    /**
     * @return AMQPChannel
     * @throws WrongArgumentException
     * @throws AMQPConnectionException
     */
	protected function getChannelLink(): AMQPChannel
	{
		if (null === $this->link) {
			$this->link = new AMQPChannel(
				$this->getTransport()->getLink()
			);
		}

		return $this->link;
	}

    /**
     * @return static
     */
	protected function checkConnection(): AMQPPeclChannel
	{
		return $this;
	}
}