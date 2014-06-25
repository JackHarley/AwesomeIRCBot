<?php
/**
 * WhoResponseParser Module
 * Parses who responses and adds/updates to the
 * users in the UserManager
 *
 * NOTE- THIS IS A SYSTEM MODULE, REMOVING IT MAY
 * 	   REMOVE VITAL FUNCTIONALITY FROM THE BOT
 *
 * Copyright © 2014, Jack P. Harley, jackpharley.com.
 * All Rights Reserved
 */
namespace modules\parsers;

use awesomeircbot\module\Module;
use awesomeircbot\user\UserManager;

class WhoResponseParser extends Module {
	
	public static $requiredUserLevel = 0;
	
	public function run() {

		$workingLine = explode(" ", $this->runMessage, 4);
		$message = $workingLine[3];

		// Chan, ident, host, server, nick
		$workingLine = explode(" ", $message);
		//$channel = $workingLine[0];
		$ident = $workingLine[1];
		$host = $workingLine[2];
		//$server = $workingLine[3];
		$nickname = $workingLine[4];
		
		// Real name
		$workingLine = explode(":", $message);
		$hopsAndRealName = trim($workingLine[1]);
		$workingHopsAndRealName = explode(" ", $hopsAndRealName, 2);
		$realName = $workingHopsAndRealName[1];

		// Fetch and update the user object
		$u = UserManager::get($nickname);
		$u->nickname = $nickname;
		$u->ident = $ident;
		$u->host = $host;
		$u->realName = $realName;
		UserManager::store($nickname, $u);
			
	}
}
?>