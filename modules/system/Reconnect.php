<?php
/**
 * Reconnect Module
 * Disconnects from the server and then reconnects
 *
 * NOTE- THIS IS A SYSTEM MODULE, REMOVING IT MAY
 * 	   REMOVE VITAL FUNCTIONALITY FROM THE BOT
 *
 * Copyright (c) 2013, Jack Harley
 * All Rights Reserved
 */
namespace modules\system;

use awesomeircbot\module\Module;
use awesomeircbot\server\Server;

class Reconnect extends Module {
	
	public static $requiredUserLevel = 10;
	
	public function run() {
		$server = Server::getInstance();
		$server->notify($this->senderNick, "Disconnecting and reconnecting as according to config values...");
		$server->quit();
		sleep(3);
	}
}
?>