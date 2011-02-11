<?php
/**
 * SystemCommands Module Config
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */
 
namespace modules\configs;
use awesomeircbot\module\ModuleConfig;

class SystemCommands implements ModuleConfig {
	
	public static $mappedCommands = array(
		"quit" => "modules\QuitFromServer",
	);
	
	public static $mappedEvents = array(
	);
	
	public static $mappedTriggers = array(
	);
}
?>