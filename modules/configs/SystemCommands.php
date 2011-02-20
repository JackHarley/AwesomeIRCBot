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
		"join" => "modules\Join",
		"help" => "modules\Help",
	);
	
	public static $mappedEvents = array(
		ReceivedLineTypes::PING => "modules\PongServer",
		ReceivedLineTypes::JOIN => "modules\JoinParser",
		ReceivedLineTypes::PART => "modules\PartParser",
		ReceivedLineTypes::PRIVMSG => "modules\MessageParser",
		ReceivedLineTypes::CHANMSG => "modules\MessageParser",
		ReceivedLineTypes::SERVERREPLYTHREEONEONE => "modules\WhoisResponseParser",
		ReceivedLineTypes::SERVERREPLYTHREETHREEZERO => "modules\WhoisResponseParser",
		ReceivedLineTypes::SERVERREPLYTHREEZEROSEVEN => "modules\WhoisResponseParser",
		ReceivedLineTypes::SERVERREPLYTHREEFIVETHREE => "modules\NamesResponseParser",
	);
	
	public static $mappedTriggers = array(
		"/(a|A)wesome(b|B)ot: (q|Q)uit the server/" => "modules\QuitFromServer",
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
		)
	);
			
}
?>