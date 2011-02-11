<?php
/**
 * QuitFromServer Module
 * Quits the server and stops script execution
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

class QuitFromServer extends Module {
	
	public function run() {
		$server = Server::getInstance();
		$server->message($this->runNick, "Shutting down...");
		$server->quit();
	}
}
?>