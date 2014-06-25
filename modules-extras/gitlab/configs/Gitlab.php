<?php
/**
 * Gitlab Module Config
 *
 * Copyright © 2014, Jack P. Harley, jackpharley.com.
 * All Rights Reserved
 */
namespace modules\gitlab\configs;

use awesomeircbot\module\ModuleConfig;
use awesomeircbot\line\ReceivedLineTypes;

class Gitlab implements ModuleConfig {

	public static $mappedCommands = array(
	);

	public static $mappedEvents = array(
		ReceivedLineTypes::PING => ["\modules\gitlab\Monitor"]
	);

	public static $mappedTriggers = array(
	);

	public static $help = array(
	);
}
?>