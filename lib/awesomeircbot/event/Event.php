<?php
/**
 * Event class
 * If it appears that a line received is
 * a mapped event, we use the event class
 * to execute the function associated with
 * the event
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */

namespace awesomeircbot\event;

use awesomeircbot\module\ModuleManager;

class Event {
	
	public $fullLine;
	public $senderNick;
	public $targetNick;
	public $type;
	
	public function __construct($lineObject) {
		$this->fullLine = $lineObject->line;
		$this->senderNick = $lineObject->senderNick;
		$this->type = $lineObject->type;
	}
		
	public function execute() {
		ModuleManager::runEvent($this->type, $this->fullLine, $this->senderNick);
	}
}
?>