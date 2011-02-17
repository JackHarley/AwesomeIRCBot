<?php
/**
 * FunStuff Module Config
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */
 
namespace modules\configs;
use awesomeircbot\module\ModuleConfig;
use awesomeircbot\line\ReceivedLineTypes;

class FunStuff implements ModuleConfig {
	
	public static $mappedCommands = array(
		"slap" => "modules\Slap",
		"harass" => "modules\Harass",
	);
	
	public static $mappedEvents = array(
		ReceivedLineTypes::CHANMSG => "modules\CheckHarass",
	);
	
	public static $mappedTriggers = array(
	);
	
	public static $help = array(
		"slap" => array(
			"BASE" => array(
				"description" => "Slaps the given user", 
				"parameters" => "<nickname>"
			)
		),
		
		"harass" => array(
			"BASE" => array(
				"description" => "Lists out all the nicknames and hostnames currently on the harass list",
				"parameters" => false
			),
			"nick" => array(
				"description" => "Manages nicknames on the harass list",
				"parameters" => "<add|del> <nickname>"
			),
		)
	);
}
?>