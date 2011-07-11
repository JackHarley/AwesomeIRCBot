<?php
/**
 * Topic Module
 * Gets the topic for the configured channel
 * This will trigger the UpdateReportsNumberInTopic module
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */
namespace modules\apptrackr;

use awesomeircbot\module\Module;
use awesomeircbot\server\Server;

class Topic extends Module {
	
	public static $requiredUserLevel = 10;
	public static $channelToGetTopic = "#LinkHunters";
	
	public function run() {
		$server = Server::getInstance();
		$server->topic(static::$channelToGetTopic);
	}
}
?>