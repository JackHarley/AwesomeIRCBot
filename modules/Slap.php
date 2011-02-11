<?php
/**
 * Slap Module
 * Slaps the user given
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */
namespace modules;

use awesomeircbot\module\Module;
use awesomeircbot\server\Server;

class Slap extends Module {
	
	public function run() {
		$server = Server::getInstance();
		$server->message($this->channel, "Let's all slap " . $this->parameters(1));
	}
}
?>