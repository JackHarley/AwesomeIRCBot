<?php
/**
 * Join Module
 * Joins a given channel
 *
 * Copyright © 2014, Jack P. Harley, jackpharley.com.
 * All Rights Reserved
 */
namespace modules\general;

use awesomeircbot\config\Config;
use awesomeircbot\module\Module;
use awesomeircbot\server\Server;

class Join extends Module {
	
	public static $requiredUserLevel = 10;
	
	public function run() {

		$channels = Config::getValue("channels");
		if (!in_array($this->parameters(1), $channels))
			$channels[] = $this->parameters(1);
		Config::setValue("channels", $channels);

		$server = Server::getInstance();
		$server->join($this->parameters(1));
	}
}
?>