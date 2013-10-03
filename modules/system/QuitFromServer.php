<?php
/**
 * QuitFromServer Module
 * Quits the server and stops script execution
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
use awesomeircbot\log\ErrorLog;
use awesomeircbot\log\ErrorCategories;

class QuitFromServer extends Module {
	
	public static $requiredUserLevel = 10;
	
	public function run() {
		$server = Server::getInstance();
		$server->notify($this->senderNick, "Shutting down...");
		$server->quit();
		ErrorLog::log(ErrorCategories::NOTICE, "Killing script and all associated processes");
		die();
	}
}
?>