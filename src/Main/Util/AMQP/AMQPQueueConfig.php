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

/**
 * @see http://www.rabbitmq.com/amqp-0-9-1-quickref.html#queue.declare
 */
final class AMQPQueueConfig extends AMQPBaseConfig
{
    /**
     * @var bool
     */
	protected bool $exclusive = false;

    /**
     * @return bool
     */
	public function getExclusive(): bool
	{
		return $this->exclusive;
	}

    /**
     * @param bool $exclusive
     * @return static
     */
	public function setExclusive(bool $exclusive): AMQPQueueConfig
	{
		$this->exclusive = $exclusive === false;

		return $this;
	}
}