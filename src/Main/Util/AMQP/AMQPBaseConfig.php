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

use OnPHP\Core\Exception\UnimplementedFeatureException;

abstract class AMQPBaseConfig
{
    /**
     * @var bool
     */
	protected bool $passive = false;
    /**
     * @var bool
     */
	protected bool $durable = false;
    /**
     * @var bool
     */
	protected bool $autodelete = false;
    /**
     * @var bool
     */
	protected bool $nowait = false;
    /**
     * @var array
     */
	protected array $arguments = array();

    /**
     * @return static
     **/
    public static function create(): AMQPBaseConfig
    {
        return new static;
    }

    /**
     * @return bool
     */
	public function getPassive(): bool
	{
		return $this->passive;
	}

    /**
     * @param bool $passive
     * @return static
     */
	public function setPassive(bool $passive): AMQPBaseConfig
	{
		$this->passive = $passive === true;

		return $this;
	}

    /**
     * @return bool
     */
	public function getDurable():bool
	{
		return $this->durable;
	}

    /**
     * @param bool $durable
     * @return static
     */
	public function setDurable(bool $durable): AMQPBaseConfig
	{
		$this->durable = $durable === true;

		return $this;
	}

    /**
     * @return bool
     */
	public function getAutodelete(): bool
	{
		return $this->autodelete;
	}

    /**
     * @param bool $autodelete
     * @return static
     */
	public function setAutodelete(bool $autodelete): AMQPBaseConfig
	{
		$this->autodelete = $autodelete === true;

		return $this;
	}

    /**
     * @return bool
     */
	public function getNowait(): bool
	{
		return $this->nowait;
	}

    /**
     * @param bool $nowait
     * @return static
     */
	public function setNowait(bool $nowait): AMQPBaseConfig
	{
		$this->nowait = $nowait === true;

		return $this;
	}

    /**
     * @param array $assoc
     * @return static
     */
	public function setArguments(array $assoc): AMQPBaseConfig
	{
		$this->arguments = $assoc;

		return $this;
	}

    /**
     * @param AMQPBitmaskResolver $resolver
     * @return int it's bitmask
     * @throws UnimplementedFeatureException
     */
	public function getBitmask(AMQPBitmaskResolver $resolver): int
	{
		return $resolver->getBitmask($this);
	}

    /**
     * @return array
     */
	public function getArguments(): array
	{
		return $this->arguments;
	}
}