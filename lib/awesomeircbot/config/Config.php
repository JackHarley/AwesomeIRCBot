<?php
/**
 * Config class
 * Handles configuration
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */

namespace awesomeircbot\data;

class Config {
	
	protected static $config = array();
	
	public function setValue($key, $data) {
		self::$config[$key] = $data;
	}
	
	public function getValue($key) {
		if (self::$config[$key])
			return self::$config[$key];
		else
			return false;
	}
}