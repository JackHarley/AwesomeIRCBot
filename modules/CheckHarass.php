<?php
/**
 * CheckHarass Module
 * Checks if a user has been added to the
 * harass list and harasses them if they are
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */
namespace modules;

use awesomeircbot\module\Module;
use awesomeircbot\server\Server;
use awesomeircbot\channel\ChannelManager;
use awesomeircbot\data\DataManager;

class CheckHarass extends Module {
	
	public static $requiredUserLevel = 0;
	
	public function run() {
		$harassedNicks = DataManager::retrieve("harassedNicks", "modules\Harass");
		$harassedHosts = DataManager::retrieve("harassedHosts", "modules\Harass");
		
		if ($harassedNicks) {
			if (in_array($this->senderNick, $harassedNicks) !== false) {
				$server = Server::getInstance();
				$server->message($this->channel, "Shutup " . $this->senderNick . "! We all hate you.");
				return true;
			}
		}
		
		if ($harassedHosts) {
			$user = UserManager::get($this->senderNick);
			$host = $user->host;
			
			if (in_array($host, $harassedHosts) !== false) {
				$server = Server::getInstance();
				$server->message($this->channel, "Shutup " . $this->senderNick . "! We all hate you.");
				return true;
			}
		}
	}
}
?>