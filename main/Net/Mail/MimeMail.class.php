<?php
/***************************************************************************
 *   Copyright (C) 2006-2007 by Konstantin V. Arkhipov                     *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 *   Based on PEAR's Mail::MIME by Richard Heyes                           *
 *                                                                         *
 ***************************************************************************/

	/**
	 * @ingroup Mail
	**/
	final class MimeMail implements MailBuilder
	{
		private $parts = array();

		// should be built by build()
		private $body		= null;
		private $headers	= null;
		
		private $boundary	= null;
		
		private $contentType	= null;
		
		public static function create()
		{
			return new self;
		}
		
		public function __construct()
		{
			// useful defaults
			$this->contentType      = 'multipart/mixed';
		}
		
		/**
		 * @return MimeMail
		**/
		public function setContentType($type)
		{
			$this->contentType = $type;
		
			return $this;
		}
		
		/**
		 * @return MimeMail
		**/
		public function addPart(MimePart $part)
		{
			$this->parts[] = $part;
			
			return $this;
		}
		
		/**
		 * @return MimeMail
		**/
		public function build()
		{
			if (!$this->parts)
				throw new UnimplementedFeatureException();
			
			if (!$this->boundary)
				$this->boundary = '=_'.md5(microtime(true)*rand(1, 1000));
			
			$mail =
				MimePart::create()->
				setContentType($this->contentType)->
				setBoundary($this->boundary);
			
			$this->headers =
				"MIME-Version: 1.0\n"
				.$mail->getHeaders();

			foreach ($this->parts as $part)
				$this->body .=
					'--'.$this->boundary."\n"
					.$part->getHeaders()
					."\n\n"
					.$part->getEncodedBody()."\n";
			
			$this->body .= '--'.$this->boundary."--"."\n\n";
			
			return $this;
		}
		
		public function getEncodedBody()
		{
			Assert::isTrue(
				$this->body && $this->headers
			);
			
			return $this->body;
		}
		
		public function getHeaders()
		{
			Assert::isTrue(
				$this->body && $this->headers
			);
			
			return $this->headers;
		}
		
		/**
		 * @return MimeMail
		**/
		public function setBoundary($boundary)
		{
			$this->boundary = $boundary;
			
			return $this;
		}
		
		public function getBoundary()
		{
			return $this->boundary;
		}
	}
?>