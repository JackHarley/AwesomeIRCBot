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
		
		switch ($this->parameters(1)) {
			case "add":
				switch ($this->parameters(2)) {
					case "nick":
						$harassedNicks = DataManager::retrieve("harassedNicks");
						if (!$harassedNicks)
							$harassedNicks = array();
						
						$harassedNicks[] = $this->parameters(3);
						DataManager::store("harassedNicks", $harassedNicks);
						
						$server = Server::getInstance();
						$server->message($this->senderNick, $this->parameters(3) . " added to harass list");
					break;
				}
			break;
			
			case "del":
				switch ($this->parameters(2)) {
					case "nick":
						$harassedNicks = DataManager::retrieve("harassedNicks");
						foreach($harassedNicks as $id => $harassedNick) {
							if ($harassedNick == $this->parameters(3))
								unset($harassedNicks[$id]);
								$success = true;
						}
						DataManager::store("harassedNicks", $harassedNicks);
						
						$server = Server::getInstance();
						if ($success)
							$server->message($this->senderNick, $this->parameters(3) . " removed from harass list");
						else
							$server->message($this->senderNick, "No harass entry found matching the nickname " . $this->parameters(3));
					break;
				}
			break;
		}
	}
}
?>