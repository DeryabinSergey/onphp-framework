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

abstract class AMQPDefaultConsumer implements AMQPConsumer
{
    /**
     * @var AMQPChannelInterface|null
     */
	protected ?AMQPChannelInterface $channel = null;
	protected ?string $consumerTag = null;
	protected ?bool $autoAcknowledge = false;
	protected ?string $queueName = null;

    /**
     * AMQPDefaultConsumer constructor.
     * @param AMQPChannelInterface $channel
     */
	public function __construct(AMQPChannelInterface $channel)
	{
		$this->channel = $channel;
	}

    /**
     * @return AMQPChannelInterface|null
     */
	public function getChannel(): ?AMQPChannelInterface
	{
		return $this->channel;
	}

    /**
     * @param string $consumerTag
     * @return static
     */
	public function setConsumerTag(string $consumerTag): AMQPDefaultConsumer
	{
		$this->consumerTag = $consumerTag;

		return $this;
	}

    /**
     * @return string
     */
	public function getConsumerTag(): ?string
	{
		return $this->consumerTag;
	}

    /**
     * @param string $consumerTag
     */
	public function handleConsumeOk(string $consumerTag = null): void
	{
		// no work to do
	}

    /**
     * @param string $consumerTag
     */
	public function handleCancelOk(string $consumerTag): void
	{
		// no work to do
	}

    /**
     * @param AMQPIncomingMessage $delivery
     * @return bool
     */
	public function handleDelivery(AMQPIncomingMessage $delivery): bool
	{
		// no work to do
	}

    /**
     * @param string $fromTag
     * @param string $toTag
     */
	public function handleChangeConsumerTag(string $fromTag, string $toTag): void
	{
		// no work to do
	}

    /**
     * @param string $name
     * @return static
     */
	public function setQueueName(string $name): AMQPDefaultConsumer
	{
		$this->queueName = $name;

		return $this;
	}

    /**
     * @return string|null
     */
	public function getQueueName(): ?string
	{
		return $this->queueName;
	}

    /**
     * @param bool $boolean
     * @return static
     */
	public function setAutoAcknowledge(bool $boolean): AMQPDefaultConsumer
	{
		$this->autoAcknowledge = ($boolean === true);

		return $this;
	}

    /**
     * @return bool
     */
	public function isAutoAcknowledge(): bool
	{
		return $this->autoAcknowledge;
	}

    /**
     * @return AMQPIncomingMessage|null
     */
	public function getNextDelivery(): ?AMQPIncomingMessage
	{
		return
            $this->channel instanceof AMQPChannelInterface
                ? $this->channel->getNextDelivery()
                : null;
	}
}