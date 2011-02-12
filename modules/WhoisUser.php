<?php
/**
 * WhoisUser Module
 * Sends a WHOIS query for the user given
 *
 * NOTE- THIS IS A SYSTEM MODULE, REMOVING IT MAY
 * 	   REMOVE VITAL FUNCTIONALITY FROM THE BOT
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */
namespace modules;

use awesomeircbot\module\Module;
use awesomeircbot\server\Server;

class WhoisUser extends Module {
	
	public function run() {
		$server = Server::getInstance();
		$server->whois($this->parameters(1));
	}
}
?>