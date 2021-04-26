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

final class AMQPOutgoingMessage extends AMQPBaseMessage
{
    /**
     * @var bool
     */
	protected bool $mandatory = false;
    /**
     * @var bool
     */
	protected bool $immediate = false;

    /**
     * @param AMQPBitmaskResolver $config
     * @return int
     * @throws UnimplementedFeatureException
     */
	public function getBitmask(AMQPBitmaskResolver $config): int
	{
		return $config->getBitmask($this);
	}

    /**
     * @return bool
     */
	public function getMandatory(): bool
	{
		return $this->mandatory;
	}

    /**
     * @param bool $mandatory
     * @return static
     */
	public function setMandatory(bool $mandatory): AMQPOutgoingMessage
	{
		$this->mandatory = $mandatory;

		return $this;
	}

    /**
     * @return bool
     */
	public function getImmediate(): bool
	{
		return $this->immediate;
	}

    /**
     * @param bool $immediate
     * @return static
     */
	public function setImmediate(bool $immediate): AMQPOutgoingMessage
	{
		$this->immediate = $immediate;

		return $this;
	}
}