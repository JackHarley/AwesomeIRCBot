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
use awesomeircbot\trigger\Trigger;

use awesomeircbot\database\Database;

/**
 * If we're not in CLI, running clear could fuck stuff up, so
 * check whether we're in CLI or not. Also, different commands
 * for Windoze and Unix, so run the correct command.
 */
if (PHP_SAPI === 'CLI')
{
	$command = (stristr(PHP_OS, 'win')) ? 'cls' : 'clear';
	passthru($command);
}

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

echo "Welcome to Awesome IRC Bot v2 Seriously Unstable Edition\n";
echo "Created by AwesomezGuy, follow @AwesomezGuy on Twitter\n";

if (Config::$die)
	die("READ THE CONFIG!\n\n");
if (Config::$configVersion != 3)
	die("Your config is out of date, please delete your old config and remake your config from config.example.php\n\n");

ModuleManager::initialize();

$server = Server::getInstance();

$db = Database::getInstance();
$db->updateScriptArrays();
$db->updateDatabase();

echo "\n";

while (true) {
	
	// Connect
	echo "Connecting to server...";
	if ($server->connect() === false) 
		die("Failed to connect to the server, check your connection details in the config!\n\n");
	echo "done!\n";
	
	// Identify
	echo "Sending identification details to server...";
	$server->identify();
	echo "done!\n";
	
	sleep(1);
	
	// NickServ
	if (Config::$nickservPassword) {
		echo "Attempting to identify with NickServ...";
		$server->identifyWithNickServ();
		echo "done!\n";
	}
	
	// Loop through the channels in the config and join them
	foreach(Config::$channels as $channel) {
		echo "Joining " . $channel . "...";
		$server->join($channel);
		echo "done!\n";
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
		
		if ($line->isMappedTrigger()) {
			$trigger = new Trigger($line);
			$trigger->execute();
		}
		
		$db->updateDatabase();
	}
	// Disconnected, Give the server 2 seconds before we attempt a reconnect
	echo "Connection lost...\nCycling around to reconnect in 10 seconds...\n\n";
	sleep(2);
}
?>
