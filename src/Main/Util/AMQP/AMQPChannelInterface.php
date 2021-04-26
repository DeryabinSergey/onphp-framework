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

interface AMQPChannelInterface
{
    /**
     * @return bool
     */
	public function isOpen(): bool;

    /**
     * @return static
     */
	public function open(): AMQPChannelInterface;

    /**
     * @return static
     */
	public function close(): AMQPChannelInterface;

    /**
     * @param string $name
     * @param AMQPExchangeConfig $conf
     * @return static
     */
	public function exchangeDeclare(string $name, AMQPExchangeConfig $conf): AMQPChannelInterface;

    /**
     * @param string $name
     * @param bool $ifUnused
     * @return static
     */
	public function exchangeDelete(string $name, bool $ifUnused = false): AMQPChannelInterface;

    /**
     * @param string $destinationName
     * @param string $sourceName
     * @param string $routingKey
     * @return static
     * @see http://www.rabbitmq.com/blog/2010/10/19/exchange-to-exchange-bindings/
     */
	public function exchangeBind(string $destinationName, string $sourceName, string $routingKey): AMQPChannelInterface;

    /**
     * @param string $destinationName
     * @param string $sourceName
     * @param string $routingKey
     * @return static
     */
	public function exchangeUnbind(string $destinationName, string $sourceName, string $routingKey): AMQPChannelInterface;

    /**
     * @param string $name
     * @param AMQPQueueConfig $conf
     * @return int the message count in queue
     */
	public function queueDeclare(string $name, AMQPQueueConfig $conf): int;

    /**
     * @param string $name
     * @param string $exchange
     * @param string $routingKey
     * @return static
     */
	public function queueBind(string $name, string $exchange, string $routingKey): AMQPChannelInterface;

    /**
     * @param string $name
     * @param string $exchange
     * @param string $routingKey
     * @return static
     */
	public function queueUnbind(string $name, string $exchange, string $routingKey): AMQPChannelInterface;

    /**
     * @param string $name
     * @return static
     */
	public function queuePurge(string $name): AMQPChannelInterface;

    /**
     * @param string $name
     * @return static
     */
	public function queueDelete(string $name): AMQPChannelInterface;

    /**
     * @param string $exchange
     * @param string $routingKey
     * @param AMQPOutgoingMessage $msg
     * @return static
     */
	public function basicPublish(string $exchange, string $routingKey, AMQPOutgoingMessage $msg): AMQPChannelInterface;

    /**
     * @param int $prefetchSize
     * @param int $prefetchCount
     * @return static
     */
	public function basicQos(int $prefetchSize, int $prefetchCount): AMQPChannelInterface;

    /**
     * @param string $queue
     * @param bool $autoAck
     * @return AMQPIncomingMessage
     */
	public function basicGet(string $queue, bool $autoAck = true): AMQPIncomingMessage;

    /**
     * @param string $deliveryTag
     * @param bool $multiple
     * @return static
     */
	public function basicAck(string $deliveryTag, bool $multiple = false): AMQPChannelInterface;

    /**
     * @param string $queue
     * @param bool $autoAck
     * @param AMQPConsumer $callback
     * @return static
     */
	public function basicConsume(string $queue, bool $autoAck, AMQPConsumer $callback): AMQPChannelInterface;

    /**
     * @param string $consumerTag
     * @return static
     */
	public function basicCancel(string $consumerTag): AMQPChannelInterface;
}