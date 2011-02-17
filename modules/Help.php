<?php
/**
 * Help Module
 * Responds to help requests
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */
namespace modules;

use awesomeircbot\module\Module;
use awesomeircbot\help\HelpManager;
use awesomeircbot\server\Server;
use config\Config;

class Help extends Module {
	
	public static $requiredUserLevel = 0;
	
	public function run() {
		$command = $this->parameters(1);

		$subcommand = $this->parameters(2);
		
		if ($subcommand != "") {
			$description = HelpManager::getDescription($command, $subcommand);
			$parameters = HelpManager::getParameters($command, $subcommand);
		}
		else {
			$description = HelpManager::getDescription($command);
			$parameters = HelpManager::getParameters($command);
		}
		
		$server = Server::getInstance();
		$server->notify($this->senderNick, "************************************");
		$server->notify($this->senderNick, "Help for " . Config::$commandCharacter . $command . " " . $subcommand);
		$server->notify($this->senderNick, "");
		$server->notify($this->senderNick, $description);
		$server->notify($this->senderNick, "Syntax: " . Config::$commandCharacter . $command . " " . $subcommand . " " . $parameters);
		$server->notify($this->senderNick, "************************************");
	}
}
?>