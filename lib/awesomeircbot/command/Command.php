<?php
/**
 * Command Class
 * If it appears that a message sent is
 * a user command, we use the command class
 * to execute the function associated with
 * the command
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */

namespace awesomeircbot\command;

use awesomeircbot\module\ModuleManager;

class Command {
	
	public $fullMessage;
	public $senderNick;
	public $command;
	
	public function __construct($lineObject) {
		$this->fullMessage = $lineObject->message;
		$this->senderNick = $lineObject->senderNick;
		echo $this->command = $lineObject->getCommand();
	}
		
	public function execute() {
		ModuleManager::runCommand($this->command, $this->fullMessage, $this->senderNick);
	}
}
?>