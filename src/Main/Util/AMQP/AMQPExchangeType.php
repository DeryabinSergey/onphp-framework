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

use OnPHP\Core\Base\Enumeration;

final class AMQPExchangeType extends Enumeration
{
	const DIRECT = 1;
	const FANOUT = 2;
	const TOPIC = 3;
	const HEADER = 4;

    /**
     * @var string[]
     */
	protected $names = array(
		self::DIRECT => "direct",
		self::FANOUT => "fanout",
		self::TOPIC => "topic",
		self::HEADER => "header"
	);

    /**
     * @return int
     */
	public function getDefault(): int
	{
		return self::DIRECT;
	}

    /**
     * @return bool
     */
	public function isDirect(): bool
	{
		return $this->id == self::DIRECT;
	}

    /**
     * @return bool
     */
	public function isFanout(): bool
	{
		return $this->id == self::FANOUT;
	}

    /**
     * @return bool
     */
	public function isTopic(): bool
	{
		return $this->id == self::TOPIC;
	}

    /**
     * @return bool
     */
	public function isHeader(): bool
	{
		return $this->id == self::HEADER;
	}
}