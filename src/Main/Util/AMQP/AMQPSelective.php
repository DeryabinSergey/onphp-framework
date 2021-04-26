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

use OnPHP\Core\Base\Assert;
use OnPHP\Core\Exception\MissingElementException;
use OnPHP\Core\Exception\WrongArgumentException;
use OnPHP\Main\Util\AMQP\Exception\AMQPServerConnectionException;

final class AMQPSelective implements AMQPInterface
{
    /**
     * @var AMQPChannelInterface[]
     */
	protected array $channels = [];
    /**
     * @var string
     */
	protected static string $proxy = AMQPProxyChannel::class;
	/**
	 * @var string|null
	 */
	private ?string $current = null;
    /**
     * @var AMQPInterface[]
     */
	private array $pool = array();

    /**
     * @return static
     */
	public static function me(): AMQPSelective
	{
		return new self;
	}

    /**
     * @param string $proxy
     * @throws WrongArgumentException
     */
	public static function setProxy(string $proxy): void
	{
	    Assert::isInstance(
	        $proxy,
            AMQPChannelInterface::class,
            'proxy must be implement`s ' . AMQPChannelInterface::class
        );
		self::$proxy = $proxy;
	}

    /**
     * @param AMQPPool $pool
     * @return static
     * @throws WrongArgumentException
     */
	public function addPool(AMQPPool $pool): AMQPSelective
	{
		foreach ($pool->getList() as $name => $amqp) {
			$this->addLink($name, $amqp);

			if ($name == 'default') {
                $this->setCurrent('default');
            }
		}

		return $this;
	}

    /**
     * @param string $name
     * @param AMQP $amqp
     * @return static
     * @throws WrongArgumentException
     */
	public function addLink(string $name, AMQP $amqp): AMQPSelective
	{
		if (isset($this->pool[$name])) {
            throw new WrongArgumentException(
                "amqp link with name '{$name}' already registered"
            );
        }

		if (!empty($this->pool)) {
            Assert::isInstance($amqp, current($this->pool));
        }

		$this->pool[$name] = $amqp;

		return $this;
	}

    /**
     * @param string $name
     * @return static
     * @throws MissingElementException
     */
	public function dropLink(string $name): AMQPSelective
	{
		if (!isset($this->pool[$name])) {
            throw new MissingElementException(
                "amqp link with name '{$name}' not found"
            );
        }

		unset($this->pool[$name]);
		if ($name == $this->current) {
            $this->current = null;
        }

		return $this;
	}

    /**
     * @param int $id
     * @return AMQPChannelInterface
     * @throws AMQPServerConnectionException
     * @throws WrongArgumentException
     */
	public function createChannel(int $id): AMQPChannelInterface
	{
		if (isset($this->channels[$id])) {
            throw new WrongArgumentException(
                "AMQP channel with id '{$id}' already registered"
            );
        }

		if (null === $this->current) {
            $this->setCurrent($this->getAlive());
        }

		if (!$this->isConnected()) {
            $this->connect();
        }

		$this->channels[$id] = new self::$proxy(
			$this->getCurrentItem()->spawnChannel($id, $this)
		);
		$this->channels[$id]->open();

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
	**/
	public function getChannelList(): array
	{
		return $this->channels;
	}

    /**
     * @param int $id
     * @return static
     * @throws MissingElementException
     */
	public function dropChannel(int $id): AMQPSelective
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
     * @return AMQPInterface
     * @throws AMQPServerConnectionException
     * @throws WrongArgumentException
     */
	public function connect(): AMQPInterface
	{
		return $this->processMethod('connect');
	}

    /**
     * @return AMQPInterface
     * @throws WrongArgumentException
     */
	public function disconnect(): AMQPInterface
	{
		return $this->processMethod('disconnect');
	}

    /**
     * @return AMQPInterface
     * @throws WrongArgumentException
     */
	public function reconnect(): AMQPInterface
	{
		return $this->processMethod('reconnect');
	}

    /**
     * @return bool
     * @throws WrongArgumentException
     */
	public function isConnected(): bool
	{
		return $this->processMethod('isConnected');
	}

    /**
     * @return object|null
     * @throws WrongArgumentException
     */
	public function getLink(): ?object
	{
		return $this->processMethod('getLink');
	}

    /**
     * @return AMQPCredentials
     * @throws WrongArgumentException
     */
	public function getCredentials(): AMQPCredentials
	{
		return $this->processMethod('getCredentials');
	}

    /**
     * @return bool
     * @throws WrongArgumentException
     */
	public function isAlive(): bool
	{
		return $this->processMethod('isAlive');
	}

    /**
     * @param bool $alive
     * @return AMQPInterface
     * @throws WrongArgumentException
     */
	public function setAlive(bool $alive): AMQPInterface
	{
		return $this->processMethod('setAlive', $alive);
	}

    /**
     * @param string $method
     * @param ...$args
     * @return false|mixed
     * @throws WrongArgumentException
     */
	protected function processMethod(string $method, ...$args)
	{
		for ($i = 0; $i < count($this->pool); $i++) {
			try {
                $currentPool = $this->getCurrentItem()->connect();
				return call_user_func_array(
				    [$currentPool, $method],
                    $args
                );
			} catch (AMQPServerConnectionException $e) {
				$this->setCurrent($this->getAlive());
			}
		}
	}

	/**
	 * @throws WrongArgumentException
	 * @return string
	 */
	public function getAlive(): string
	{
		foreach ($this->pool as $name => $item) {
			if ($item->isAlive()) {
                return $name;
            }
		}

		Assert::isUnreachable("no alive connection");
	}

    /**
     * @param string $name
     * @return static
     * @throws WrongArgumentException
     */
	public function setCurrent(string $name): AMQPSelective
	{
		Assert::isIndexExists($this->pool, $name);
		$this->current = $name;

		return $this;
	}

    /**
     * @return AMQPInterface
     * @throws WrongArgumentException
     */
	protected function getCurrentItem(): AMQPInterface
	{
		if ($this->current && $this->pool[$this->current]->isAlive()) {
            return $this->pool[$this->current];
        }

		Assert::isUnreachable("no current connection");
	}
}