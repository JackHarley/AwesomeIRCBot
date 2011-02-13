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
		"identify" => "modules\Identify",
	);
	
	public static $mappedEvents = array(
		ReceivedLineTypes::PING => "modules\PongServer",
		ReceivedLineTypes::SERVERREPLYTHREEONEONE => "modules\WhoisResponseParser",
		ReceivedLineTypes::SERVERREPLYTHREETHREEZERO => "modules\WhoisResponseParser",
		ReceivedLineTypes::SERVERREPLYTHREEFIVETHREE => "modules\NamesResponseParser",
	);
	
	public static $mappedTriggers = array(
	);
}
?>