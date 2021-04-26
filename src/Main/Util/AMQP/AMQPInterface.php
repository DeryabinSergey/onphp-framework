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

use OnPHP\Core\Exception\MissingElementException;
use OnPHP\Core\Exception\WrongArgumentException;
use OnPHP\Main\Util\AMQP\Exception\AMQPServerConnectionException;

/**
 * AMQP stands for Advanced Message Queue Protocol, which is
 * an open standard middleware layer for message routing and queuing.
**/
interface AMQPInterface
{
    /**
     * @return static
     * @throws AMQPServerConnectionException
     * @throws WrongArgumentException
     */
	public function connect(): AMQPInterface;

	/**
	 * @return static
     * @throws WrongArgumentException
	**/
	public function disconnect(): AMQPInterface;

    /**
     * @return static
     * @throws WrongArgumentException
     */
	public function reconnect(): AMQPInterface;

    /**
     * @return bool
     * @throws WrongArgumentException
     */
	public function isConnected(): bool;

    /**
     * @return object|null
     * @throws WrongArgumentException
     */
	public function getLink(): ?object;

    /**
     * @param int $id
     * @return AMQPChannelInterface
     * @throws AMQPServerConnectionException
     * @throws WrongArgumentException
     */
	public function createChannel(int $id): AMQPChannelInterface;

    /**
     * @param int $id
     * @return AMQPChannelInterface
     * @throws MissingElementException
     */
	public function getChannel(int $id): AMQPChannelInterface;

    /**
     * @return AMQPChannelInterface[]
     */
	public function getChannelList(): array;

    /**
     * @param int $id
     * @return static
     * @throws MissingElementException
     */
	public function dropChannel(int $id): AMQPInterface;

    /**
     * @return AMQPCredentials
     */
	public function getCredentials(): AMQPCredentials;

    /**
     * @return bool
     * @throws WrongArgumentException
     */
	public function isAlive(): bool;

    /**
     * @param bool $alive
     * @return static
     */
	//public function setAlive(bool $alive): AMQPInterface;
}