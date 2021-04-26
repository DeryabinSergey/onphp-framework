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
 * @see http://www.rabbitmq.com/amqp-0-9-1-quickref.html#exchange.declare
**/
final class AMQPExchangeConfig extends AMQPBaseConfig
{
    /**
     * @var bool|null
     */
	protected ?bool $internal = null;
    /**
     * @var AMQPExchangeType|null
     */
	protected ?AMQPExchangeType $type = null;

    /**
     * @param AMQPExchangeType $type
     * @return static
     */
	public function setType(AMQPExchangeType $type): AMQPExchangeConfig
	{
		$this->type = $type;

		return $this;
	}

    /**
     * @return AMQPExchangeType|null
     */
	public function getType(): ?AMQPExchangeType
	{
		return $this->type;
	}

    /**
     * @return bool
     */
	public function getInternal(): bool
	{
		return $this->internal;
	}

    /**
     * @param bool $internal
     * @return static
     */
	public function setInternal(bool $internal): AMQPExchangeConfig
	{
		$this->internal = $internal;

		return $this;
	}		
}