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

use OnPHP\Core\Exception\WrongArgumentException;
use OnPHP\Main\Util\AMQP\Exception\AMQPServerConnectionException;

/**
 * Base class modelling an AMQ channel
**/
abstract class AMQPBaseChannel implements AMQPChannelInterface
{
    /**
     * @var int|null
     */
	protected ?int $id = null;
    /**
     * @var AMQPInterface|null
     */
	protected ?AMQPInterface $transport = null;

	public function __construct(int $id, AMQPInterface $transport)
	{
		$this->id = $id;
		$this->transport = $transport;
	}

	public function __destruct()
	{
		if ($this->isOpen()) {
            $this->close();
        }
	}

    /**
     * @return AMQPInterface
     */
	public function getTransport(): AMQPInterface
	{
		return $this->transport;
	}

    /**
     * @return int|null
     */
	public function getId(): ?int
	{
		return $this->id;
	}

    /**
     * @return static
     * @throws AMQPServerConnectionException
     */
	protected function checkConnection(): AMQPBaseChannel
	{
	    $isConnected = false;
	    try {
	        $isConnected =
                !$this->transport instanceof AMQPInterface
                || !$this->transport->getLink()
                || !$this->transport->getLink()->isConnected();
        } catch (WrongArgumentException $exception) { /**  */ }

        if (!$isConnected) {
            throw new AMQPServerConnectionException("No connection available");
        }

		return $this;
	}
}