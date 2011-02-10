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

// Clear the CLI
passthru('clear');

// Print welcome message
echo "Welcome to Awesome IRC Bot v2 Seriously Unstable Edition\n";
echo "Created by AwesomezGuy, follow @AwesomezGuy on Twitter\n\n";

// Check config
if (Config::$die)
	die("READ THE CONFIG!\n\n");

// Get Server instance
$server = Server::getInstance();

// Connect
if ($server->connect() === false) 
	die("Failed to connect to the server, check your connection details in the config!");

// Identify
$server->identify();

// Map the system commands
ModuleManager::map("quit", "modules\QuitFromServer");

// Loop-edy-loop
while($server->connected()) {
	$line = $server->getNextLine();
	
	$line = new ReceivedLine($line);
	$line->parse();
	
	if ($line->isCommand) {
		$command = new Command($line);
		$command->run();
	}
}

// FUCK
die("Connection died!");
?>