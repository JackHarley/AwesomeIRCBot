<?php
/**
 * Error Log Class
 * Should be used to log any actions the bot takes
 *
 * Copyright © 2014, Jack P. Harley, jackpharley.com.
 * All Rights Reserved
 */

namespace awesomeircbot\log;

use awesomeircbot\log\Error;
use awesomeircbot\config\Config;

class ErrorLog {

	public static $log = array();
	public static $fileHandle;
	
	private function __construct() {}
	
	/**
	 * Logs an 'error'
	 * 
	 * @param int bit error type, see the ErrorCategories constant class
	 * @param string message
	 */
	public static function log($type, $message) {
		$error = new Error($type, $message);
		
		if ((Config::getRequiredValue("verboseOutput") & $error->type) === $error->type) {
			echo "[" . date("d/m/y H:i:s", $error->epoch) . "] " . $error->message . "\n";
			if (!static::$fileHandle)
				static::$fileHandle = fopen(__DIR__ . "/../../../logs/run-" . date("d-m-y") .  ".log", "a");
			fwrite(static::$fileHandle, "[" . date("d/m/y H:i:s", $error->epoch) . "] " . $error->message . "\n");
		}
		
		if ($error->type == ErrorCategories::FATAL) {
			echo "DYING " . $error->message;
			echo "\n";
			die();
			exit();
		}
	}
}
?>