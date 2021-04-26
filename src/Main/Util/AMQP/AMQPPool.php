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

use OnPHP\Core\Base\Singleton;
use OnPHP\Core\Base\Instantiatable;
use OnPHP\Core\Exception\WrongArgumentException;
use OnPHP\Core\Exception\MissingElementException;

/**
 * @todo BasePool implementations
**/
final class AMQPPool extends Singleton implements Instantiatable
{
	private ?AMQP $default = null;
	private array $pool = array();

    /**
     * @return static
     * @throws MissingElementException
     * @throws WrongArgumentException
     */
	public static function me(): AMQPPool
	{
		return Singleton::getInstance(__CLASS__);
	}

    /**
     * @param AMQP $amqp
     * @return static
     */
	public function setDefault(AMQP $amqp): AMQPPool
	{
		$this->default = $amqp;

		return $this;
	}

    /**
     * @return static
     */
	public function dropDefault(): AMQPPool
	{
		$this->default = null;

		return $this;
	}

    /**
     * @param string $name
     * @param AMQP $amqp
     * @return static
     * @throws WrongArgumentException
     */
	public function addLink(string $name, AMQP $amqp): AMQPPool
	{
		if (isset($this->pool[$name]))
			throw new WrongArgumentException(
				"amqp link with name '{$name}' already registered"
			);

		$this->pool[$name] = $amqp;

		return $this;
	}

    /**
     * @param string $name
     * @return static
     * @throws MissingElementException
     */
	public function dropLink(string $name): AMQPPool
	{
		if (!isset($this->pool[$name])) {
            throw new MissingElementException(
                "amqp link with name '{$name}' not found"
            );
        }

		unset($this->pool[$name]);

		return $this;
	}

    /**
     * @param string|null $name
     * @return AMQP
     * @throws Exception\AMQPServerConnectionException
     * @throws MissingElementException
     * @throws WrongArgumentException
     */
	public function getLink(string $name = null): AMQP
	{
		$link = null;

		// single-amqp project
		if (!$name) {
			if (!$this->default) {
                throw new MissingElementException(
                    'i have no default amqp link and '
                    . 'requested link name is null'
                );
            }

			$link = $this->default;
		} elseif (isset($this->pool[$name])) {
            $link = $this->pool[$name];
        }

		if ($link) {
			if (!$link->isConnected()) {
                $link->connect();
            }

			return $link;
		}

		throw new MissingElementException(
			"can't find amqp link with '{$name}' name"
		);
	}

    /**
     * @return static
     * @throws WrongArgumentException
     */
	public function shutdown(): AMQPPool
	{
		$this->disconnect();

		$this->default = null;
		$this->pool = array();

		return $this;
	}

    /**
     * @return static
     * @throws WrongArgumentException
     */
	public function disconnect(): AMQPPool
	{
		if ($this->default) {
            $this->default->disconnect();
        }

		foreach ($this->pool as $amqp) {
            $amqp->disconnect();
        }

		return $this;
	}

    /**
     * @return array
     */
	public function getList(): array
	{
		$list = $this->pool;

		try {
			$list['default'] = $this->getLink();
		} catch (MissingElementException $e) {/**/}

		return $list;
	}
}