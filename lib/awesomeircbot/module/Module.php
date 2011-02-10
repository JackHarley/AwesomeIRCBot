<?php
/**
 * Module class
 * Should be extended by any module for the bot
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */

namespace awesomeircbot\module;

class Module {
	
	public $runLine;
	public $runNick;
	public $runUser;
	
	public function __construct($runLine, $runNick, $runUser) {
		$this->runLine = $runLine;
		$this->runNick = $runNick;
		$this->runUser = $runUser;
	}
}
?>