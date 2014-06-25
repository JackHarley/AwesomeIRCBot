<?php
/**
 * Log Module Config
 *
 * Copyright © 2014, Jack P. Harley, jackpharley.com.
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
		ReceivedLineTypes::JOIN => "modules\log\LogChannelJoin",
		ReceivedLineTypes::PART => "modules\log\LogChannelPart",
		ReceivedLineTypes::NICK => "modules\log\LogNicknameChange",
	);
	
	public static $mappedTriggers = array(
	);

	public static $help = array(
	);
			
}
?>