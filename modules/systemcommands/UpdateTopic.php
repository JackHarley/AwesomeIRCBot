<?php
/**
 * UpdateTopic Module
 * Gets the topic for the given channel otr alternatively
 * loops through all connected channels and updates the topic for
 * them
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
use awesomeircbot\channel\ChannelManager;

class UpdateTopic extends Module {
	
	public static $requiredUserLevel = 10;
	
	public function run() {
		$server = Server::getInstance();
		
		if ($this->eventType) {
			$channels = ChannelManager::getConnectedChannelArray();
			
			foreach ($channels as $channel) {
				$server->topic($channel);
			}
		}
		else {
			$server->topic($this->parameters(1));
		}
	}
}
?>