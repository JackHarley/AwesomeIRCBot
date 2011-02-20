<?php
/**
 * MessageParser Module
 * Parses messages and adds the users
 * to the UserManager
 *
 * NOTE- THIS IS A SYSTEM MODULE, REMOVING IT MAY
 * 	   REMOVE VITAL FUNCTIONALITY FROM THE BOT
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */
namespace modules;

use awesomeircbot\module\Module;
use awesomeircbot\server\Server;
use awesomeircbot\user\User;
use awesomeircbot\user\UserManager;
use config\Config;

class MessageParser extends Module {
	
	public static $requiredUserLevel = 0;
	
	public function run() {
		
		$user = UserManager::get($this->senderNick);
		
		/*************
		 * PARSE IT! *
		 *************/
		 
		// User
		$workingLine = explode(" PRIVMSG", $this->runMessage);
		$workingLine[0] = str_replace(":", "", $workingLine[0]);
		
			// Nick
			$workingLine = explode("!", $workingLine[0]);
			$user->nickname = $workingLine[0];
			
			// Ident
			$workingLine = explode("@", $workingLine[1]);
			$user->ident = $workingLine[0];
			
			// Host
			$user->host = $workingLine[1];
		
		UserManager::store($this->senderNick, $user);
	}
}
?>