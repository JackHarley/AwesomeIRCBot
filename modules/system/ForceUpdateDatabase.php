<?php
/**
 * Force update server Module
 * Changes all the last updated times in the config and module data sets
 * to now, to force the database to reload them
 *
 * NOTE- THIS IS A SYSTEM MODULE, REMOVING IT MAY
 * 	   REMOVE VITAL FUNCTIONALITY FROM THE BOT
 *
 * Copyright © 2014, Jack P. Harley, jackpharley.com.
 * All Rights Reserved
 */
namespace modules\system;

use awesomeircbot\module\Module;
use awesomeircbot\server\Server;
use awesomeircbot\data\DataManager;
use awesomeircbot\config\Config;

class ForceUpdateDatabase extends Module {
	
	public static $requiredUserLevel = 0;
	
	public function run() {
		Config::changeAllTimestampsToNow();
		DataManager::changeAllTimestampsToNow();
		
		$server = Server::getInstance();
		$server->notify($this->senderNick, "Database has reloaded all values from RAM");
	}
}
?>