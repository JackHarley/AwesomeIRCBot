<?php
/**
 * LogChannelMessage Module
 * Logs a channel message to the database
 *
 * NOTE- THIS IS A SYSTEM MODULE, REMOVING IT MAY
 * 	   REMOVE VITAL FUNCTIONALITY FROM THE BOT
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */
namespace modules\log;

use awesomeircbot\module\Module;
use awesomeircbot\server\Server;

class LogChannelMessage extends Module {
	
	public static $requiredUserLevel = 0;
	
	public function run() {
	}
}
?>