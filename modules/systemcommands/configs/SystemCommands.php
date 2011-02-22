<?php
/**
 * SystemCommands Module Config
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */
namespace modules\systemcommands\configs;
use awesomeircbot\module\ModuleConfig;
use awesomeircbot\line\ReceivedLineTypes;

class SystemCommands implements ModuleConfig {
	
	public static $mappedCommands = array(
		"quit" => "modules\systemcommands\QuitFromServer",
		"identify" => "modules\systemcommands\Identify",
		"join" => "modules\systemcommands\Join",
		"help" => "modules\systemcommands\Help",
		"module" => "modules\systemcommands\ModuleControls",
	);
	
	public static $mappedEvents = array(
		ReceivedLineTypes::PING => "modules\systemcommands\PongServer",
		ReceivedLineTypes::JOIN => "modules\systemcommands\JoinParser",
		ReceivedLineTypes::PART => "modules\systemcommands\PartParser",
		ReceivedLineTypes::PRIVMSG => "modules\systemcommands\MessageParser",
		ReceivedLineTypes::CHANMSG => "modules\systemcommands\MessageParser",
		ReceivedLineTypes::SERVERREPLYTHREEONEONE => "modules\systemcommands\WhoisResponseParser",
		ReceivedLineTypes::SERVERREPLYTHREETHREEZERO => "modules\systemcommands\WhoisResponseParser",
		ReceivedLineTypes::SERVERREPLYTHREEZEROSEVEN => "modules\systemcommands\WhoisResponseParser",
		ReceivedLineTypes::SERVERREPLYTHREEFIVETHREE => "modules\systemcommands\NamesResponseParser",
	);
	
	public static $mappedTriggers = array(
		"/(a|A)wesome(b|B)ot: (q|Q)uit the server/" => "modules\systemcommands\QuitFromServer",
	);

	public static $help = array(
		"quit" => array(
			"BASE" => array(
				"description" => "Quits the bot from the server", 
				"parameters" => false
			)
		),
		
		"identify" => array(
			"BASE" => array(
				"description" => "Attempts to identify you with the bot using MickServ and the permission level specified in the config",
				"parameters" => false
			)
		),
		
		"join" => array(
			"BASE" => array(
				"description" => "Joins the given channel",
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
	);
			
}
?>