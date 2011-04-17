<?php
/**
 * PrivilegedUserControls Module
 * Manages privileged users
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
use config\Config;

class PrivilegedUserControls extends Module {
	
	public static $requiredUserLevel = 10;
	
	public function run() {
	
		$user = $this->parameters(2);
		$level = $this->parameters(3);
	}
	
	public function add() {
		
		$user = $this->parameters(2);
		$level = $this->parameters(3);
		
		$server = Server::getInstance();
		
		Config::$users[$user] = $level;
		$server->notify($this->senderNick, $user . " added to privileged users list at level " . $level);
				
		$server->whois($user);
	}
	
	public function del() {
		
		$action = $this->parameters(1);
		$user = $this->parameters(2);
		$level = $this->parameters(3);
		
		$server = Server::getInstance();
		
		unset(Config::$users[$user]);
		$server->notify($this->senderNick, $user . " removed from privileged users list");
				
		$server->whois($user);
	}
}
?>