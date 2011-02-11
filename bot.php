<?php
/* Awesome IRC Bot v2 Seriously Unstable Edition
 * Created by AwesomezGuy
 * bashIRC snools
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */

require_once(__DIR__ . "/config/config.php");
require_once(__DIR__ . "/lib/awesomeircbot/awesomeircbot.inc.php");
require_once(__DIR__ . "/modules/modules.inc.php");

use config\Config;
use awesomeircbot\server\Server;
use awesomeircbot\module\ModuleManager;
use awesomeircbot\line\ReceivedLine;
use awesomeircbot\line\ReceivedLineTypes;
use awesomeircbot\command\Command;
use awesomeircbot\event\Event;

passthru('clear');
error_reporting(0);
echo "Welcome to Awesome IRC Bot v2 Seriously Unstable Edition\n";
echo "Created by AwesomezGuy, follow @AwesomezGuy on Twitter\n\n";

if (Config::$die)
	die("READ THE CONFIG!");

ModuleManager::loadModuleConfig('modules\configs\SystemCommands');

$server = Server::getInstance();

while (true) {
	
	// Connect
	if ($server->connect() === false) 
		die("Failed to connect to the server, check your connection details in the config!");
	
	// Identify
	$server->identify();
	
	// Stabilise everything
	sleep(2);
	
	// Loop through the channels in the config and join them
	foreach(Config::$channels as $channel) {
		$server->join($channel);
	}
	
	// Loop-edy-loop
	while($server->connected()) {
		$line = $server->getNextLine();
		
		$line = new ReceivedLine($line);
		$line->parse();
		
		if ($line->isCommand()) {
			$command = new Command($line);
			$command->execute();
		}
		
		if ($line->isMappedEvent()) {
			$event = new Event($line);
			$event->execute();
		}
	}
}
?>