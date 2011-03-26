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
use awesomeircbot\database\Database;
use awesomeircbot\user\UserManager;
use awesomeircbot\line\ReceivedLine;

class LogChannelMessage extends Module {
	
	public static $requiredUserLevel = 0;
	
	public function run() {
		$line = new ReceivedLine($this->runMessage);
		$line->parse();
		
		$user = UserManager::get($this->senderNick);
		$db = Database::getInstance();
		
		$stmt = $db->prepare("INSERT INTO channel_messages (nickname, host, ident, channel_name, message, time) VALUES (?,?,?,?,?,?)");
		$stmt->execute(array($this->senderNick, $user->host, $user->ident, $this->channel, $line->message, time()));
	}
}
?>