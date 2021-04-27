<?php
/***************************************************************************
 *   Copyright (C) 2012 by Evgeniya Tekalin                                *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/

namespace OnPHP\Main\Util\AMQP;

use Throwable;
use OnPHP\Main\Util\AMQP\Exception\AMQPServerException;
use OnPHP\Core\Exception\WrongArgumentException;

/**
 * Base class modelling an AMQ channel
**/
class AMQPProxyChannel implements AMQPChannelInterface
{
    /**
     * @var AMQPChannelInterface|null
     */
	protected ?AMQPChannelInterface $channel = null;

	public function __construct(AMQPChannelInterface $channel)
	{
		$this->channel = $channel;
	}

    /**
     * @return bool
     */
	public function isOpen(): bool
	{
		return $this->channel->isOpen();
	}

    /**
     * @return AMQPChannelInterface
     */
	public function open(): AMQPChannelInterface
	{
		return $this->channel->open();
	}

    /**
     * @return AMQPChannelInterface
     */
	public function close(): AMQPChannelInterface
	{
		return $this->channel->close();
	}

    /**
     * @param string $name
     * @param AMQPExchangeConfig $conf
     * @return AMQPChannelInterface
     * @throws AMQPServerException
     */
	public function exchangeDeclare($name, AMQPExchangeConfig $conf): AMQPChannelInterface
	{
		try {
			return $this->channel->exchangeDeclare($name, $conf);
		} catch (AMQPServerException $e) {
			return $this->
				transportReconnect($e)->
				exchangeDeclare($name, $conf);
		}
	}

    /**
     * @param string $name
     * @param bool $ifUnused
     * @return AMQPChannelInterface
     * @throws AMQPServerException
     */
	public function exchangeDelete(string $name, bool $ifUnused = false): AMQPChannelInterface
	{
		try {
			return $this->channel->exchangeDelete($name, $ifUnused);
		} catch (AMQPServerException $e) {
			return $this->
				transportReconnect($e)->
				exchangeDelete($name, $ifUnused);
		}
	}

    /**
     * @param string $destinationName
     * @param string $sourceName
     * @param string $routingKey
     * @return AMQPChannelInterface
     * @throws AMQPServerException
     */
	public function exchangeBind(string $destinationName, string $sourceName, string $routingKey): AMQPChannelInterface
	{
		try {
			return $this->channel->exchangeBind(
				$destinationName,
				$sourceName,
				$routingKey
			);
		} catch (AMQPServerException $e) {
			return $this->
				transportReconnect($e)->
				exchangeBind(
					$destinationName,
					$sourceName,
					$routingKey
				);
		}
	}

    /**
     * @param string $destinationName
     * @param string $sourceName
     * @param string $routingKey
     * @return AMQPChannelInterface
     * @throws AMQPServerException
     */
	public function exchangeUnbind(string $destinationName, string $sourceName, string $routingKey): AMQPChannelInterface
	{
		try {
			return $this->channel->exchangeUnbind(
				$destinationName,
				$sourceName,
				$routingKey
			);
		} catch (AMQPServerException $e) {
			return $this->
				transportReconnect($e)->
				exchangeUnbind(
					$destinationName,
					$sourceName,
					$routingKey
				);
		}
	}

    /**
     * @param string $name
     * @param AMQPQueueConfig $conf
     * @return int
     * @throws AMQPServerException
     */
	public function queueDeclare(string $name, AMQPQueueConfig $conf): int
	{
		try {
			return $this->channel->queueDeclare($name, $conf);
		} catch (AMQPServerException $e) {
			return $this->
				transportReconnect($e)->
				queueDeclare($name, $conf);
		}
	}

    /**
     * @param string $name
     * @param string $exchange
     * @param string $routingKey
     * @return AMQPChannelInterface
     * @throws AMQPServerException
     */
	public function queueBind(string $name, string $exchange, string $routingKey): AMQPChannelInterface
	{
		try {
			return $this->channel->queueBind(
				$name,
				$exchange,
				$routingKey
			);
		} catch (AMQPServerException $e) {
			return $this->
				transportReconnect($e)->
				queueBind(
					$name,
					$exchange,
					$routingKey
				);
		}
	}

    /**
     * @param string $name
     * @param string $exchange
     * @param string $routingKey
     * @return AMQPChannelInterface
     * @throws AMQPServerException
     */
	public function queueUnbind(string $name, string $exchange, string $routingKey): AMQPChannelInterface
	{
		try {
			return $this->channel->queueUnbind(
				$name,
				$exchange,
				$routingKey
			);
		} catch (AMQPServerException $e) {
			return $this->
				transportReconnect($e)->
				queueUnbind(
					$name,
					$exchange,
					$routingKey
				);
		}
	}

    /**
     * @param string $name
     * @return AMQPChannelInterface
     * @throws AMQPServerException
     */
	public function queuePurge(string $name): AMQPChannelInterface
	{
		try {
			return $this->channel->queuePurge($name);
		} catch (AMQPServerException $e) {
			return $this->
				transportReconnect($e)->
				queuePurge($name);
		}
	}

    /**
     * @param string $name
     * @return AMQPChannelInterface
     * @throws AMQPServerException
     */
	public function queueDelete(string $name): AMQPChannelInterface
	{
		try {
			return $this->channel->queueDelete($name);
		} catch (AMQPServerException $e) {
			return $this->
				transportReconnect($e)->
				queueDelete($name);
		}
	}

    /**
     * @param string $exchange
     * @param string $routingKey
     * @param AMQPOutgoingMessage $msg
     * @return AMQPChannelInterface
     * @throws AMQPServerException
     */
	public function basicPublish(string $exchange, string $routingKey, AMQPOutgoingMessage $msg): AMQPChannelInterface
	{
		try {
			return $this->channel->basicPublish(
				$exchange,
				$routingKey,
				$msg
			);
		} catch (AMQPServerException $e) {
			return $this->
				transportReconnect($e)->
				basicPublish($exchange, $routingKey, $msg);
		}
	}

    /**
     * @param int $prefetchSize
     * @param int $prefetchCount
     * @return AMQPChannelInterface
     * @throws AMQPServerException
     */
	public function basicQos(int $prefetchSize, int $prefetchCount): AMQPChannelInterface
	{
		try {
			return $this->channel->basicQos($prefetchSize, $prefetchCount);
		} catch (AMQPServerException $e) {
			return $this->
				transportReconnect($e)->
				basicQos($prefetchSize, $prefetchCount);
		}
	}

    /**
     * @param string $queue
     * @param bool $autoAck
     * @return AMQPIncomingMessage
     * @throws AMQPServerException
     */
	public function basicGet(string $queue, bool $autoAck = true): AMQPIncomingMessage
	{
		try {
			return $this->channel->basicGet($queue, $autoAck);
		} catch (AMQPServerException $e) {
			return $this->
				transportReconnect($e)->
				basicGet($queue, $autoAck);
		}
	}

    /**
     * @param string $deliveryTag
     * @param bool $multiple
     * @return AMQPChannelInterface
     * @throws AMQPServerException
     */
	public function basicAck(string $deliveryTag, bool $multiple = false): AMQPChannelInterface
	{
		try {
			return $this->channel->basicAck($deliveryTag, $multiple);
		} catch (AMQPServerException $e) {
			return $this->
				transportReconnect($e)->
				basicAck($deliveryTag, $multiple);
		}
	}

    /**
     * @param string $queue
     * @param bool $autoAck
     * @param AMQPConsumer $callback
     * @return AMQPChannelInterface
     * @throws AMQPServerException
     */
	public function basicConsume(string $queue, bool $autoAck, AMQPConsumer $callback): AMQPChannelInterface
	{
		try {
			return $this->channel->basicConsume($queue, $autoAck, $callback);
		} catch (AMQPServerException $e) {
			return $this->
				transportReconnect($e)->
				basicConsume($queue, $autoAck, $callback);
		}
	}

    /**
     * @param string $consumerTag
     * @return AMQPChannelInterface
     * @throws AMQPServerException
     */
	public function basicCancel(string $consumerTag): AMQPChannelInterface
	{
		try {
			return $this->channel->basicCancel($consumerTag);
		} catch (AMQPServerException $e) {
			return $this->
				transportReconnect($e)->
				basicCancel($consumerTag);
		}
	}

    /**
     * @param Throwable $e
     * @return AMQPProxyChannel
     * @throws AMQPServerException
     */
	protected function transportReconnect(Throwable $e): AMQPProxyChannel
	{
		$this
            ->markAlive(false)
            ->reconnect($e);

		return $this;
	}

    /**
     * @param bool $alive
     * @return static
     */
	private function markAlive(bool $alive = false): AMQPProxyChannel
	{
		try {
			$this->channel->getTransport()->
				setAlive($alive);
		} catch (WrongArgumentException $e) {/*no_connection*/}

		return $this;
	}

    /**
     * @param Throwable $amqpException
     * @throws AMQPServerException
     */
	private function reconnect(Throwable $amqpException): void
	{
		try {
			$this->channel->getTransport()->
				setCurrent(
					$this->channel->getTransport()->getAlive()
				);
		} catch (WrongArgumentException $e) {
			throw new AMQPServerException(
				$amqpException->getMessage(),
				$amqpException->getCode(),
				$amqpException
			);
		}
	}
}