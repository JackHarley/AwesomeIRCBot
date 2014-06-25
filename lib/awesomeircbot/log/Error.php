<?php
/**
 * Error Class
 * An action/error the bot takes
 *
 * Copyright © 2014, Jack P. Harley, jackpharley.com.
 * All Rights Reserved
 */

namespace awesomeircbot\log;

class Error {

	public $type;
	public $message;
	public $epoch;

	public function __construct($type, $message) {
		$this->type = $type;
		$this->message = trim(preg_replace('/\s+/', ' ', $message));
		$this->epoch = time();
	}
}
?>