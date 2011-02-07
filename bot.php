<?php
/* Awesome IRC Bot v2 Seriously Unstable Edition
 * Created by AwesomezGuy
 * bashIRC snools
 */
require_once(__DIR__ . "/config/config.php");
require_once(__DIR__ . "/lib/awesomeircbot/awesomeircbot.inc.php");

use config\Config;
use awesomeircbot\server\Server;

// Clear the CLI
passthru('clear');

// Print welcome message
echo "Welcome to Awesome IRC Bot v2 Seriously Unstable Edition\n";
echo "Created by AwesomezGuy, follow @AwesomezGuy on Twitter\n\n";

// Check config
if (Config::$die)
	die("READ THE CONFIG!\n\n");

// Load the config into the server class
Server::$serverName = Config::$serverName;
Server::$serverAddress = Config::$serverAddress;
Server::$serverPort = Config::$serverPort;

// Connect
$server = Server::getInstance();

if ($server->connect() === false) 
	die("Failed to connect to the server, check your connection details in the config!");

// Identify
$server->identify();

// Loop-edy-loop
while($server->connected())
	$server->getNextLine();

// FUCK
die("Connection died!");
?>