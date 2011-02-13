<?php
/**
 * Harass Module Config
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */
 
namespace modules\configs;
use awesomeircbot\module\ModuleConfig;
use awesomeircbot\line\ReceivedLineTypes;

class Harass implements ModuleConfig {
	
	public static $mappedCommands = array(
		"harass" => "modules\Harass",
	);
	
	public static $mappedEvents = array(
		ReceivedLineTypes::CHANMSG => "modules\CheckHarass",
	);
	
	public static $mappedTriggers = array(
	);
}
?>