<?php
/***************************************************************************
 *   Copyright (C) 2012 by Evgeniya Tekalin                                *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/

namespace OnPHP\Main\Util\AMQP\Pecl;

use AMQPEnvelope;
use OnPHP\Core\Exception\WrongArgumentException;
use OnPHP\Main\Util\AMQP\AMQPDefaultConsumer;
use OnPHP\Main\Util\AMQP\AMQPIncomingMessage;

abstract class AMQPPeclQueueConsumer extends AMQPDefaultConsumer
{
    /**
     * @var bool
     */
	protected bool $cancel = false;
    /**
     * @var int
     */
	protected int $count = 0;
    /**
     * @var int
     */
	protected int $limit = 0;

    /**
     * @param bool $cancel
     * @return static
     */
	public function setCancel(bool $cancel): AMQPPeclQueueConsumer
	{
		$this->cancel = ($cancel === true);

		return $this;
	}

    /**
     * @param int $limit
     * @return static
     */
	public function setLimit(int $limit): AMQPPeclQueueConsumer
	{
		$this->limit = $limit;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getCount(): int
    {
		return $this->count;
	}

    /**
     * @param AMQPEnvelope $delivery
     * @return bool
     * @throws WrongArgumentException
     */
	public function handlePeclDelivery(AMQPEnvelope $delivery): bool
	{
		$this->count++;

		if ($this->limit && $this->count >= $this->limit)
			$this->setCancel(true);

		return $this->handleDelivery(
			AMQPPeclIncomingMessageAdapter::convert($delivery)
		);
	}

    /**
     * @param AMQPIncomingMessage $delivery
     * @return bool
     */
	public function handleDelivery(AMQPIncomingMessage $delivery): bool
	{
		if ($this->cancel) {
			$this->handleCancelOk('');
			return false;
		}

		return true;
	}
}