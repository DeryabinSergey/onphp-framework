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

namespace OnPHP\Main\Util\AMQP;

use OnPHP\Core\Base\Assert;
use OnPHP\Core\Exception\WrongArgumentException;

final class AMQPIncomingMessage extends AMQPBaseMessage
{
	const COUNT = 'count';
	const ROUTING_KEY = 'routing_key';
	const DELIVERY_TAG = 'delivery_tag';
	const EXCHANGE = 'exchange';
	const BODY = 'msg';
	const CONSUME_BODY = 'message_body';
	const CONSUMER_TAG = 'consumer_tag';
	const REDELIVERED = 'redelivered';

    /**
     * @var int
     */
	protected int $count = 0;
    /**
     * @var string|null
     */
	protected ?string $routingKey = null;
    /**
     * @var string|null
     */
	protected ?string $exchange = null;
    /**
     * @var string|null
     */
	protected ?string $deliveryTag = null;
    /**
     * @var string|null
     */
	protected ?string $redelivered = null;
    /**
     * @var string|null
     */
	protected ?string $consumerTag = null;

	protected static array $mandatoryFields = [
		self::ROUTING_KEY,
        self::DELIVERY_TAG,
        self::EXCHANGE
	];

    /**
     * @param array $assoc
     * @return static
     * @throws WrongArgumentException
     */
	public static function spawn(array $assoc): AMQPIncomingMessage
	{
		return static::create()->fill($assoc);
	}

    /**
     * @return string|null
     */
	public function getRedelivered(): ?string
	{
		return $this->redelivered;
	}

    /**
     * @param string $redelivered
     * @return static
     */
	public function setRedelivered(string $redelivered): AMQPIncomingMessage
	{
		$this->redelivered = $redelivered;

		return $this;
	}

    /**
     * @return ?string
     */
	public function getConsumerTag(): string
	{
		return $this->consumerTag;
	}

    /**
     * @param string $consumerTag
     * @return static
     */
	public function setConsumerTag(string $consumerTag): AMQPIncomingMessage
	{
		$this->consumerTag = $consumerTag;

		return $this;
	}

	public function getCount()
	{
		return $this->count;
	}

    /**
     * @param int $count
     * @return static
     */
	public function setCount(int $count): AMQPIncomingMessage
	{
		$this->count = $count;

		return $this;
	}

    /**
     * @return string|null
     */
	public function getRoutingKey(): ?string
	{
		return $this->routingKey;
	}

    /**
     * @param string $routingKey
     * @return static
     */
	public function setRoutingKey(string $routingKey): AMQPIncomingMessage
	{
		$this->routingKey = $routingKey;

		return $this;
	}

    /**
     * @return string|null
     */
	public function getExchange(): ?string
	{
		return $this->exchange;
	}

    /**
     * @param string $exchange
     * @return static
     */
	public function setExchange(string $exchange): AMQPIncomingMessage
	{
		$this->exchange = $exchange;

		return $this;
	}

    /**
     * @return string|null
     */
	public function getDeliveryTag(): ?string
	{
		return $this->deliveryTag;
	}

    /**
     * @param string $deliveryTag
     * @return static
     */
	public function setDeliveryTag(string $deliveryTag): AMQPIncomingMessage
	{
		$this->deliveryTag = $deliveryTag;

		return $this;
	}

    /**
     * @return bool
     */
	public function isEmptyQueue(): bool
	{
		return $this->count == -1;
	}

    /**
     * @param array $assoc
     * @return static
     * @throws WrongArgumentException
     */
	protected function fill(array $assoc): AMQPIncomingMessage
	{
		$this->checkMandatory($assoc);

		if (isset($assoc[self::COUNT])) {
			$this->setCount($assoc[self::COUNT]);
			unset($assoc[self::COUNT]);
		}

		$this->setRoutingKey($assoc[self::ROUTING_KEY]);
		$this->setDeliveryTag($assoc[self::DELIVERY_TAG]);
		$this->setExchange($assoc[self::EXCHANGE]);

		if (isset($assoc[self::BODY])) {
			$this->setBody($assoc[self::BODY]);
			unset($assoc[self::BODY]);
		}

		if (isset($assoc[self::CONSUME_BODY])) {
			$this->setBody($assoc[self::CONSUME_BODY]);
			unset($assoc[self::CONSUME_BODY]);
		}

		if (isset($assoc[self::CONSUMER_TAG])) {
			$this->setConsumerTag($assoc[self::CONSUMER_TAG]);
			unset($assoc[self::CONSUMER_TAG]);
		}

		if (isset($assoc[self::REDELIVERED])) {
			$this->setRedelivered($assoc[self::REDELIVERED]);
			unset($assoc[self::REDELIVERED]);
		}

		//unset mandatory
		unset(
			$assoc[self::ROUTING_KEY],
			$assoc[self::DELIVERY_TAG],
			$assoc[self::EXCHANGE]
		);

		$this->setProperties($assoc);

		return $this;
	}

    /**
     * @param array $assoc
     * @return static
     * @throws WrongArgumentException
     */
	protected function checkMandatory(array $assoc): AMQPIncomingMessage
	{
		foreach (self::$mandatoryFields as $field) {
			Assert::isIndexExists(
				$assoc, $field, "Mandatory field '{$field}' not found"
			);
		}

		return $this;
	}
}