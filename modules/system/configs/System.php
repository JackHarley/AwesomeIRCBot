<?php
/**
 * System Module Config
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */
namespace modules\system\configs;

use awesomeircbot\module\ModuleConfig;
use awesomeircbot\line\ReceivedLineTypes;

class System implements ModuleConfig {
	
	public static $mappedCommands = array(
		"quit" => "modules\system\QuitFromServer",
		"identify" => "modules\system\Identify",
		"help" => "modules\system\Help",
		"module" => "modules\system\ModuleControls",
		"user" => "modules\system\PrivilegedUserControls",
		"topic" => "modules\system\UpdateTopic",
		"config" => "modules\system\ConfigControls",
		"reconnect" => "modules\system\Reconnect",
	);
	
	public static $mappedEvents = array(
		ReceivedLineTypes::PING => "modules\system\PongServer",
		ReceivedLineTypes::PING => "modules\system\UpdateTopic",
	);
	
	public static $mappedTriggers = array(
	);

	public static $help = array(
		"quit" => array(
			"BASE" => array(
				"description" => "Quits the bot from the server", 
				"parameters" => false
			)
		),
		
		"reconnect" => array(
			"BASE" => array(
				"description" => "Disconnects from the server, reloads the config and then connects", 
				"parameters" => false
			)
		),
		
		"identify" => array(
			"BASE" => array(
				"description" => "Attempts to identify you with the bot using MickServ and the permission level specified in the config",
				"parameters" => false
			)
		),
		
		"topic" => array(
			"BASE" => array(
				"description" => "Updates the topic in the database for the given channel",
				"parameters" => "<#channel>"
			)
		),
		
		"module" => array(
			"BASE" => array(
				"description" => "Lists the loaded module sets",
				"parameters" => false
			),
			"load" => array(
				"description" => "Loads a module config",
				"parameters" => "<module config full namespace>"
			),
			"unload" => array(
				"description" => "Unloads a module config and all the items it loaded",
				"parameters" => "<module config full namespace>"
			),
		),
		
		"config" => array(
			"BASE" => array(
				"description" => "Lists all config values",
				"parameters" => false
			),
			"set" => array(
				"description" => "Sets a config key to the desired value",
				"parameters" => "<key> <value>"
			),
			"get" => array(
				"description" => "Gets a config value and messages it to you",
				"parameters" => "<key>"
			),
		),
		
		"user" => array(
			"BASE" => array(
				"description" => "Lists the privileged users",
				"parameters" => false
			),
			"add" => array(
				"description" => "Adds/Modifies a privileged user and their level",
				"parameters" => "<nickname> <user level>"
			),
			"del" => array(
				"description" => "Deletes a privileged user",
				"parameters" => "<nickname>"
			)
		),
	);
			
}
?>