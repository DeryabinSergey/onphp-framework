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

use OnPHP\Core\Base\Timestamp;
use OnPHP\Core\Base\Assert;
use OnPHP\Core\Exception\WrongArgumentException;

/**
 * http://www.rabbitmq.com/amqp-0-9-1-reference.html#class.basic
**/
abstract class AMQPBaseMessage
{
	const CONTENT_TYPE = 'content_type';
	const CONTENT_ENCODING = 'content_encoding';
	const MESSAGE_ID = 'message_id';
	const USER_ID = 'user_id';
	const APP_ID = 'app_id';
	const DELIVERY_MODE = 'delivery_mode';
	const PRIORITY = 'priority';
	const CORRELATION_ID = 'correlation_id';
	const TIMESTAMP = 'timestamp';
	const EXPIRATION = 'expiration';
	const TYPE = 'type';
	const REPLY_TO = 'reply_to';

	const DELIVERY_MODE_NONPERISISTENT = 1;
	const DELIVERY_MODE_PERISISTENT = 2;		

	const PRIORITY_MIN = 0;
	const PRIORITY_MAX = 9;

	protected array $properties = array();
	protected ?Timestamp $timestamp = null;
	protected ?string $body = null;

    /**
     * @return static
     */
    public static function create(): AMQPBaseMessage
    {
        return new static;
    }

    /**
     * @return string|null
     */
	public function getBody(): ?string
	{
		return $this->body;
	}

    /**
     * @param string $body
     * @return static
     */
	public function setBody(string $body): AMQPBaseMessage
	{
		$this->body = $body;

		return $this;
	}

    /**
     * @param string $key
     * @return mixed|null
     */
	public function getProperty(string $key)
	{
		if (isset($this->properties[$key]))
			return $this->properties[$key];

		return null;
	}

    /**
     * @param array $assoc
     * @return static
     */
	public function setProperties(array $assoc): AMQPBaseMessage
	{
		$this->properties = $assoc;

		if (isset($this->properties[self::TIMESTAMP]))
			$this->timestamp =
				new Timestamp($this->properties[self::TIMESTAMP]);

		return $this;
	}

    /**
     * @return array
     */
	public function getProperties(): array
	{
		return $this->properties;
	}

    /**
     * @param string $string
     * @return static
     */
	public function setContentType(string $string): AMQPBaseMessage
	{
		$this->properties[self::CONTENT_TYPE] = $string;

		return $this;
	}

    /**
     * @return string|null
     */
	public function getContentType(): ?string
	{
		return $this->getProperty(self::CONTENT_TYPE);
	}

    /**
     * @param string $string
     * @return static
     */
	public function setContentEncoding(string $string): AMQPBaseMessage
	{
		$this->properties[self::CONTENT_ENCODING] = $string;

		return $this;
	}

    /**
     * @return string|null
     */
	public function getContentEncoding(): ?string
	{
		return $this->getProperty(self::CONTENT_ENCODING);
	}

    /**
     * @param string $string
     * @return static
     */
	public function setMessageId(string $string): AMQPBaseMessage
	{
		$this->properties[self::MESSAGE_ID] = $string;

		return $this;
	}

    /**
     * @return string|null
     */
	public function getMessageId(): ?string
	{
		return $this->getProperty(self::MESSAGE_ID);
	}

    /**
     * @param string $string
     * @return static
     */
	public function setUserId(string $string): AMQPBaseMessage
	{
		$this->properties[self::USER_ID] = $string;

		return $this;
	}

    /**
     * @return string|null
     */
	public function getUserId(): ?string
	{
		return $this->getProperty(self::USER_ID);
	}

    /**
     * @param string $string
     * @return static
     */
	public function setAppId(string $string): AMQPBaseMessage
	{
		$this->properties[self::APP_ID] = $string;

		return $this;
	}

    /**
     * @return string|null
     */
	public function getAppId(): ?string
	{
		return $this->getProperty(self::APP_ID);
	}

    /**
     * Non-persistent (1) or persistent (2).
     * @param int $int
     * @return static
     * @throws WrongArgumentException
     */
	public function setDeliveryMode(int $int): AMQPBaseMessage
	{
		Assert::isIndexExists(
		    array_fill_keys(
		        [self::DELIVERY_MODE_NONPERISISTENT, self::DELIVERY_MODE_PERISISTENT],
                true
            ),
			$int,
			__METHOD__.": unknown mode {$int}"
		);

		$this->properties[self::DELIVERY_MODE] = $int;

		return $this;
	}

    /**
     * @return int|null
     */
	public function getDeliveryMode(): ?int
	{
		return $this->getProperty(self::DELIVERY_MODE);
	}

    /**
     * Message priority from 0 to 9.
     * @param int $int
     * @return static
     * @throws WrongArgumentException
     */
	public function setPriority(int $int): AMQPBaseMessage
	{
		Assert::isTrue(
			($int >= self::PRIORITY_MIN && $int <= self::PRIORITY_MAX),
			__METHOD__
		);

		$this->properties[self::PRIORITY] = $int;

		return $this;
	}

    /**
     * @return int|null
     */
	public function getPriority(): ?int
	{
		return $this->getProperty(self::PRIORITY);
	}

    /**
     * @param string $string
     * @return static
     */
	public function setCorrelationId(string $string): AMQPBaseMessage
	{
		$this->properties[self::CORRELATION_ID] = $string;

		return $this;
	}

    /**
     * @return string|null
     */
	public function getCorrelationId(): ?string
	{
		return $this->getProperty(self::CORRELATION_ID);
	}

    /**
     * @param Timestamp $datetime
     * @return static
     */
	public function setTimestamp(Timestamp $datetime): AMQPBaseMessage
	{
		$this->timestamp = $datetime;
		$this->properties[self::TIMESTAMP] = $datetime->toStamp();

		return $this;
	}

    /**
     * @return Timestamp|null
     */
	public function getTimestamp(): ?Timestamp
	{
		return $this->timestamp;
	}

    /**
     * @param string $string
     * @return static
     */
	public function setExpiration(string $string): AMQPBaseMessage
	{
		$this->properties[self::EXPIRATION] = $string;

		return $this;
	}

    /**
     * @return string|null
     */
	public function getExpiration(): ?string
	{
		return $this->getProperty(self::EXPIRATION);
	}

    /**
     * @param string $string
     * @return static
     */
	public function setType(string $string): AMQPBaseMessage
	{
		$this->properties[self::TYPE] = $string;

		return $this;
	}

    /**
     * @return string|null
     */
	public function getType(): ?string
	{
		return $this->getProperty(self::TYPE);
	}

    /**
     * @param string $string
     * @return static
     */
	public function setReplyTo(string $string): AMQPBaseMessage
	{
		$this->properties[self::REPLY_TO] = $string;

		return $this;
	}

    /**
     * @return string|null
     */
	public function getReplyTo(): ?string
	{
		return $this->getProperty(self::REPLY_TO);
	}		
}