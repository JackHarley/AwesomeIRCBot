<?php
/**
 * Error Class
 * An action/error the bot takes
 *
 * Copyright (c) 2013, Jack Harley
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