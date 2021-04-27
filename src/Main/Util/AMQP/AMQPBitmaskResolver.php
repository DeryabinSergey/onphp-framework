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

interface AMQPBitmaskResolver
{
    /**
     * @param object $config
     * @return int
     * @throws UnimplementedFeatureException
     */
	public function getBitmask(object $config): int;
}