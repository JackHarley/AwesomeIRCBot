<?php
/**
 * Log Module Config
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */
namespace modules\log\configs;
use awesomeircbot\module\ModuleConfig;
use awesomeircbot\line\ReceivedLineTypes;

class Log implements ModuleConfig {
	
	public static $mappedCommands = array(
	);
	
	public static $mappedEvents = array(
		ReceivedLineTypes::CHANMSG => "modules\log\LogChannelMessage",
	);
	
	public static $mappedTriggers = array(
	);

	public static $help = array(
	);
			
}
?>