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

use OnPHP\Core\Exception\WrongArgumentException;
use OnPHP\Core\Exception\MissingElementException;

/**
 * AMQP stands for Advanced Message Queue Protocol, which is
 * an open standard middleware layer for message routing and queuing.
**/
abstract class AMQP implements AMQPInterface
{
    /**
     * @var AMQPCredentials|null
     */
	protected ?AMQPCredentials $credentials = null;
    /**
     * @var object|null
     */
	protected ?object $link = null;
    /**
     * @var bool
     */
	protected bool $alive = true;
    /**
     * @var AMQPChannelInterface[]
     */
	protected array $channels = [];


    /**
     * @param int $id
     * @param AMQPInterface $transport
     * @return AMQP
     */
	abstract public function spawnChannel(int $id, AMQPInterface $transport): AMQPChannelInterface;

    /**
     * @param AMQPCredentials $credentials
     */
	public function __construct(AMQPCredentials $credentials)
	{
		$this->credentials = $credentials;
	}

	public function __destruct()
	{
		if ($this->isConnected()) {
			$this->disconnect();
		}
	}

    /**
     * @param string $class
     * @param AMQPCredentials $credentials
     * @return object
     */
	public static function spawn(string $class, AMQPCredentials $credentials): object
	{
		return new $class($credentials);
	}

    /**
     * @return object|null
     */
	public function getLink(): ?object
	{
		return $this->link;
	}

    /**
     * @param int $id
     * @return AMQPChannelInterface
     * @throws WrongArgumentException
     */
	public function createChannel(int $id): AMQPChannelInterface
	{
		if (isset($this->channels[$id])) {
            throw new WrongArgumentException(
                "AMQP channel with id '{$id}' already registered"
            );
        }

		if (!$this->isConnected()) {
            $this->connect();
        }

		$this->channels[$id] =
			$this
                ->spawnChannel($id, $this)
                ->open();

		return $this->channels[$id];
	}

    /**
     * @param int $id
     * @return AMQPChannelInterface
     * @throws MissingElementException
     */
	public function getChannel(int $id): AMQPChannelInterface
	{
		if (isset($this->channels[$id])) {
            return $this->channels[$id];
        }

		throw new MissingElementException(
		    "Can't find AMQP channel with id '{$id}'"
		);
	}

    /**
     * @return AMQPChannelInterface[]
     */
	public function getChannelList(): array
	{
		return $this->channels;
	}

    /**
     * @param int $id
     * @return static
     * @throws MissingElementException
     */
	public function dropChannel(int $id): AMQP
	{
		if (!isset($this->channels[$id])) {
            throw new MissingElementException(
                "AMQP channel with id '{$id}' not found"
            );
        }

		$this->channels[$id]->close();
		unset($this->channels[$id]);

		return $this;
	}

    /**
     * @return AMQPCredentials
     */
	public function getCredentials(): AMQPCredentials
	{
		return $this->credentials;
	}

    /**
     * @return bool
     */
	public function isAlive(): bool
	{
		return $this->alive;
	}

    /**
     * @param bool $alive
     * @return static
     */
	public function setAlive(bool $alive): AMQP
	{
		$this->alive = ($alive === true);

		return $this;
	}
}