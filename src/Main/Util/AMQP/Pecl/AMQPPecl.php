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

use AMQPConnection;
use AMQPConnectionException;
use Throwable;
use OnPHP\Main\Util\AMQP\AMQP;
use OnPHP\Main\Util\AMQP\AMQPCredentials;
use OnPHP\Main\Util\AMQP\Exception\AMQPServerConnectionException;
use OnPHP\Main\Util\AMQP\AMQPInterface;

/**
 * @see http://www.php.net/manual/en/book.amqp.php
**/
final class AMQPPecl extends AMQP
{
    /**
     * AMQPPecl constructor.
     * @param AMQPCredentials $credentials
     * @throws AMQPServerConnectionException
     */
	public function __construct(AMQPCredentials $credentials)
	{
		parent::__construct($credentials);

		$this->fillCredentials();
	}

    /**
     * @return bool
     */
	public function isConnected(): bool
	{
		try {
			return $this->link->isConnected();
		} catch (Throwable $e) {
			return false;
		}
	}

    /**
     * @return static
     * @throws AMQPServerConnectionException
     */
	public function connect(): AMQPPecl
	{
		try {
			if ($this->isConnected()) {
                return $this;
            }

			$this->link->connect();
		} catch (AMQPConnectionException $e) {
			$this->alive = false;

			throw new AMQPServerConnectionException($e->getMessage(), $e->getCode(), $e);
		}

		return $this;
	}

    /**
     * @return static
     * @throws AMQPServerConnectionException
     */
	public function reconnect(): AMQPPecl
	{
		try {
			$this->link->reconnect();

			return $this;
		} catch (AMQPConnectionException $e) {
			$this->alive = false;

            throw new AMQPServerConnectionException($e->getMessage(), $e->getCode(), $e);
		}
	}

    /**
     * @return static
     * @throws AMQPServerConnectionException
     */
	public function disconnect(): AMQPPecl
	{
		try {
			if ($this->isConnected()) {
				$this->link->disconnect();
			}
		} catch (AMQPConnectionException $e) {
			$this->alive = false;

            throw new AMQPServerConnectionException($e->getMessage(), $e->getCode(), $e);
		}

        return $this;
	}

    /**
     * @param int $id
     * @param AMQPInterface $transport
     * @return AMQPPeclChannel
     */
	public function spawnChannel(int $id, AMQPInterface $transport): AMQPPeclChannel
	{
		return new AMQPPeclChannel($id, $transport);
	}

    /**
     * @return static
     * @throws AMQPServerConnectionException
     */
	protected function fillCredentials(): AMQPPecl
	{
	    try {
            $this->link = new AMQPConnection();
            $this->link->setHost($this->credentials->getHost());
            $this->link->setPort($this->credentials->getPort());
            $this->link->setLogin($this->credentials->getLogin());
            $this->link->setPassword($this->credentials->getPassword());
            $this->link->setVHost($this->credentials->getVirtualHost());
        } catch(AMQPConnectionException $e) {
            throw new AMQPServerConnectionException($e->getMessage(), $e->getCode(), $e);
        }

		return $this;
	}
}