<?php
/**
 * Join Module
 * Joins a given channel
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

class Join extends Module {
	
	public static $requiredUserLevel = 10;
	
	public function run() {
		$server = Server::getInstance();
		$server->join($this->parameters(1));
	}
}
?>