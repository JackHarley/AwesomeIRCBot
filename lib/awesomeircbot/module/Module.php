<?php
/**
 * Module class
 * Should be extended by any module for the bot
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */

namespace awesomeircbot\module;

abstract class Module {
	
	public $runMessage;
	public $runNick;
	public $eventType;
	public $targetNick;
	
	public function __construct($runMessage, $runNick, $eventType=false, $targetMick=false) {
		$this->runMessage = $runMessage;
		$this->runNick = $runNick;
		$this->eventType = $eventType;
		$this->targetNick = $targetNick;
	}
}
?>