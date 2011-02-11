<?php
/**
 * SystemCommands Module Config
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */
 
namespace modules\configs;
use awesomeircbot\module\ModuleConfig;
use awesomeircbot\line\ReceivedLineTypes;

class SystemCommands implements ModuleConfig {
	
	public static $mappedCommands = array(
		"quit" => "modules\QuitFromServer",
		"slap" => "modules\Slap",
	);
	
	public static $mappedEvents = array(
		ReceivedLineTypes::PING => "modules\PongServer",
	);
	
	public static $mappedTriggers = array(
	);
}
?>