<?php
/**
 * Topic Module
 * Gets the topic for the given channel
 *
 * NOTE- THIS IS A SYSTEM MODULE, REMOVING IT MAY
 * 	   REMOVE VITAL FUNCTIONALITY FROM THE BOT
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */
namespace modules\systemcommands;

use awesomeircbot\module\Module;
use awesomeircbot\server\Server;

class Topic extends Module {
	
	public static $requiredUserLevel = 10;
	
	public function run() {
		$server = Server::getInstance();
		$server->topic($this->parameters(1));
	}
}
?>