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
	
	// The full command message sent by the user
	public $fullMessage;
	
	// The nickname of the sender/commander
	public $senderNick;
	
	// The command
	public $command;
	
	/**
	 * Construction
	 *
	 * @param object the ReceivedLine object for the command
	 */
	public function __construct($lineObject) {
		$this->fullMessage = $lineObject->message;
		$this->senderNick = $lineObject->senderNick;
		echo $this->command = $lineObject->getCommand();
	}
	
	/**
	 * Execute the command through ModuleManager
	 */
	public function execute() {
		ModuleManager::runCommand($this->command, $this->fullMessage, $this->senderNick);
	}
}
?>