<?php
/***************************************************************************
 *   Copyright (C) 2004-2006 by Dmitry E. Demidov                          *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 ***************************************************************************/
/* $Id$ */

	/**
	 * @ingroup Turing
	**/
	class Color implements Stringable
	{
		private	$red	= 0;
		private	$green	= 0;
		private	$blue	= 0;
		
		// valid values: #AABBCC, DDEEFF, A15B, etc.
		public function __construct($rgb)
		{
			$length = strlen($rgb);
			
			Assert::isTrue($length <= 7, 'color must be #XXXXXX');
			
			if ($rgb[0] == '#')
				$rgb = substr($rgb, 1);
			
			if ($length < 6)
				$rgb = str_pad($rgb, 6, '0', STR_PAD_LEFT);
			
			$this->red		= hexdec($rgb[0] . $rgb[1]);
			$this->green	= hexdec($rgb[2] . $rgb[3]);
			$this->blue		= hexdec($rgb[4] . $rgb[5]);
		}

		public function setRed($red)
		{
			$this->red = $red;
			
			return $this;
		}
		
		public function getRed()
		{
			return $this->red;
		}

		public function setGreen($green)
		{
			$this->green = $green;
			
			return $this;
		}
		
		public function getGreen()
		{
			return $this->green;
		}

		public function setBlue($blue)
		{
			$this->blue = $blue;
			
			return $this;
		}
		
		public function getBlue()
		{
			return $this->blue;
		}
	
		public function invertColor()
		{
			$this->setRed(255 - $this->getRed());
			$this->setBlue(255 - $this->getBlue());
			$this->setGreen(255 - $this->getGreen());

			return $this;
		}
		
		public function toString()
		{
			return
				str_pad(dechex($this->red), 2, '0', STR_PAD_LEFT)
				.str_pad(dechex($this->green), 2, '0', STR_PAD_LEFT)
				.str_pad(dechex($this->blue), 2, '0', STR_PAD_LEFT);
		}
	}
?>