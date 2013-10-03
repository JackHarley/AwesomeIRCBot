<?php
/**
 * PrivilegedUserControls Module
 * Manages privileged users
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
use awesomeircbot\config\Config;

class PrivilegedUserControls extends Module {

	public static $requiredUserLevel = 10;

	public function run() {

		$action = $this->parameters(1);
		$user = $this->parameters(2);
		$level = $this->parameters(3);

		$server = Server::getInstance();

		switch ($action) {
			case "add":
				$configUsers = Config::getRequiredValue("users");
				$configUsers[$user] = $level;
				Config::setValue("users", $configUsers);
				
				$server->notify($this->senderNick, $user . " added to privileged users list at level " . $level);
			break;
			case "del":
				$configUsers = Config::getRequiredValue("users");
				unset($configUsers[$user]);
				Config::setValue("users", $configUsers);
				
				$server->notify($this->senderNick, $user . " removed from privileged users list");
			break;
		}

		$server->whois($user);
	}
}
?>