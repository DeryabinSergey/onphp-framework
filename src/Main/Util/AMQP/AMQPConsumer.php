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

interface AMQPConsumer
{
    /**
     * @return AMQPChannelInterface
     */
	public function getChannel(): ?AMQPChannelInterface;

    /**
     * Called when a delivery appears for this consumer.
     * @param AMQPIncomingMessage $delivery
     * @return bool
     */
	public function handleDelivery(AMQPIncomingMessage $delivery): bool;

    /**
     * Called when the consumer is first registered by a call
     * to {@link Channel#basicConsume}.
     * @param string $consumerTag the defined consumerTag
     */
	public function handleConsumeOk(string $consumerTag): void;

    /**
     * Called when the consumer is deregistered by a call
     * to {@link Channel#basicCancel}.
     * @param string $consumerTag
     */
	public function handleCancelOk(string $consumerTag): void;

    /**
     * Called when the consumer is changed tag
     * @param string $fromTag
     * @param string $toTag
     */
	public function handleChangeConsumerTag(string $fromTag, string $toTag): void;

    /**
     * @param string $name
     * @return static
     */
	public function setQueueName(string $name): AMQPConsumer;

    /**
     * @return string
     */
	public function getQueueName(): ?string;

    /**
     * @param bool $boolean
     * @return AMQPConsumer
     */
	public function setAutoAcknowledge(bool $boolean): AMQPConsumer;

    /**
     * @return bool
     */
	public function isAutoAcknowledge(): bool;

    /**
     * @param string $consumerTag
     * @return static
     */
	public function setConsumerTag(string $consumerTag): AMQPConsumer;

    /**
     * @return string
     */
	public function getConsumerTag(): string;

    /**
     * @return ?AMQPIncomingMessage
     */
	public function getNextDelivery(): ?AMQPIncomingMessage;
}