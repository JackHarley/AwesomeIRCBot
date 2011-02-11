<?php
/**
 * PongServer Module
 * Responds to a ping request from a server
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

class PongServer extends Module {
	
	public function run() {
		$server = Server::getInstance();
		$server->pong($this->senderNick);
	}
}
?>