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

final class AMQPCredentials
{
	const DEFAULT_HOST = 'rabbit';
	const DEFAULT_PORT = '5672';
	const DEFAULT_LOGIN = 'guest';
	const DEFAULT_PASSWORD = 'guest';
	const DEFAULT_VHOST = '/';

	protected $host = null;
	protected $port = null;
	protected $virtualHost = null;
	protected $login = null;
	protected $password = null;

    /**
     * @return static
     */
	public static function create(): AMQPCredentials
	{
		return new static;
	}

	/**
	 * @return static
	**/
	public static function createDefault(): AMQPCredentials
	{
		return
			self::create()->
                setHost(self::DEFAULT_HOST)->
                setPort(self::DEFAULT_PORT)->
                setLogin(self::DEFAULT_LOGIN)->
                setPassword(self::DEFAULT_PASSWORD)->
                setVirtualHost(self::DEFAULT_VHOST);
	}

    /**
     * @return string|null
     */
	public function getHost(): ?string
	{
		return $this->host;
	}

    /**
     * @param string $host
     * @return static
     */
	public function setHost(string $host): AMQPCredentials
	{
		$this->host = $host;

		return $this;
	}

    /**
     * @return string
     */
	public function getPort(): ?string
	{
		return $this->port;
	}

    /**
     * @param string $port
     * @return static
     */
	public function setPort(string $port): AMQPCredentials
	{
		$this->port = $port;

		return $this;
	}

    /**
     * @return string|null
     */
	public function getVirtualHost(): ?string
	{
		return $this->virtualHost;
	}

    /**
     * @param string $virtualHost
     * @return static
     */
	public function setVirtualHost(string $virtualHost): AMQPCredentials
	{
		$this->virtualHost = $virtualHost;

		return $this;
	}

    /**
     * @return string|null
     */
	public function getLogin(): ?string
	{
		return $this->login;
	}

    /**
     * @param string $login
     * @return static
     */
	public function setLogin(string $login): AMQPCredentials
	{
		$this->login = $login;

		return $this;
	}

    /**
     * @return string|null
     */
	public function getPassword(): ?string
	{
		return $this->password;
	}

    /**
     * @param string $password
     * @return static
     */
	public function setPassword(string $password): AMQPCredentials
	{
		$this->password = $password;

		return $this;
	}
}