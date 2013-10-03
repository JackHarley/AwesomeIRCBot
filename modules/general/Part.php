<?php
/**
 * Part Module
 * Parts the given channel, or if no channel is
 * supplied, the current channel
 *
 * Copyright (c) 2013, Jack Harley
 * All Rights Reserved
 */
namespace modules\general;

use awesomeircbot\config\Config;
use awesomeircbot\module\Module;
use awesomeircbot\server\Server;

class Part extends Module {
	
	public static $requiredUserLevel = 10;
	
	public function run() {
		
		if ($this->parameters(1))
			$channel = $this->parameters(1);
		else
			$channel = $this->channel;

		$channels = Config::getValue("channels");
		foreach($channels as $offset => $value) {
			if ($value == $channel)
				unset($channels[$offset]);
		}
		Config::setValue("channels", $channels);

		$server = Server::getInstance();
		$server->part($channel);
	}
}
?>