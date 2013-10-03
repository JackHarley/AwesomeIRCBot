<?php
/**
 * Set Web Password Module
 * Allows fulfillment of a request
 *
 * NOTE- THIS IS A SYSTEM MODULE, REMOVING IT MAY
 * 	   REMOVE VITAL FUNCTIONALITY FROM THE BOT
 *
 * Copyright (c) 2013, Jack Harley
 * All Rights Reserved
 */
namespace modules\system;

use awesomeircbot\module\Module;
use awesomeircbot\config\Config;
use awesomeircbot\server\Server;

class SetWebPassword extends Module {
	
	public static $requireIdentification = true;
	
	public function run() {
		
		$server = Server::getInstance();
		
		$userPasswords = Config::getValue("userPasswords");
		if (!$userPasswords)
			$userPasswords = array();
		
		$userPasswords[$this->senderNick] = md5($this->parameters(1));
		Config::setValue("userPasswords", $userPasswords);
		
		$server->notify($this->senderNick, "Password for " . $this->senderNick . " set to " . $this->parameters(1));
	}
}
?>