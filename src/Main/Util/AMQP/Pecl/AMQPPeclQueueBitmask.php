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

use OnPHP\Core\Base\Assert;
use OnPHP\Core\Exception\UnimplementedFeatureException;
use OnPHP\Core\Exception\WrongArgumentException;
use OnPHP\Main\Util\AMQP\AMQPBaseConfig;
use OnPHP\Main\Util\AMQP\AMQPQueueConfig;

/**
 * @see http://www.php.net/manual/en/amqp.constants.php
 */
final class AMQPPeclQueueBitmask extends AMQPPeclBaseBitmask
{
    /**
     * @param object $config
     * @return int
     * @throws UnimplementedFeatureException
     * @throws WrongArgumentException
     */
	public function getBitmask(object $config): int
	{
		Assert::isInstance($config, AMQPQueueConfig::class);

		$bitmask = parent::getBitmask($config);

		if ($config->getExclusive()) {
            $bitmask = $bitmask | AMQP_EXCLUSIVE;
        }

		return $bitmask;
	}
}