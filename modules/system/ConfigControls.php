<?php
/**
 * Config module
 * Allows you to edit the configuration on-the-go
 *
 * NOTE- THIS IS A SYSTEM MODULE, REMOVING IT MAY
 * 	   REMOVE VITAL FUNCTIONALITY FROM THE BOT
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */
namespace modules\system;

use awesomeircbot\module\Module;

use awesomeircbot\config\Config;
use awesomeircbot\server\Server;

class ConfigControls extends Module {
	
	public static $requiredUserLevel = 10;
	
	public function run() {
		
		$action = $this->parameters(1);
		$server = Server::getInstance();
		
		switch ($action) {
			case "set":
				Config::setValue($this->parameters(2), $this->parameters(3, true));
			break;
			
			case "get":
				$value = Config::getValue($this->parameters(2));
				
				if (!$value)
					$server->notify($this->senderNick, "Config key '" . $this->parameters(2) . "' is not set");
				
				$server->notify($this->senderNick, $this->parameters(2) . " => " . $value);
			break;
		}
	}
}
?>