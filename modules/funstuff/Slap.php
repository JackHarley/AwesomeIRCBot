<?php
/**
 * Slap Module
 * Slaps the user given
 *
 * Copyright © 2014, Jack P. Harley, jackpharley.com.
 * All Rights Reserved
 */
namespace modules\funstuff;

use awesomeircbot\module\Module;
use awesomeircbot\server\Server;

class Slap extends Module {
	
	public static $requiredUserLevel = 5;
	
	public function run() {
		$server = Server::getInstance();
		$server->act($this->channel, "slaps " . $this->parameters(1));
	}
}
?>