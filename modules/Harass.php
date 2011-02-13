<?php
/**
 * Harass Module
 * Adds nicknames or hosts to the
 * harass list
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */
namespace modules;

use awesomeircbot\module\Module;
use awesomeircbot\server\Server;
use awesomeircbot\data\DataManager;

class Harass extends Module {
	
	public static $requiredUserLevel = 5;
	
	public function run() {
	
		$harassedNicks = DataManager::retrieve("harassedNicks");
		if (!$harassedNicks)
			$harassedNicks = array();
		
		$harassedNicks[] = $this->parameters(1);
		DataManager::store("harassedNicks", $harassedNicks);
		
		$server = Server::getInstance();
		$server->message($this->senderNick, $this->parameters(1) . "added to harass list");
	}
}
?>