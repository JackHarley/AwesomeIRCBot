<?php
/**
 * Startup PHP script
 * Starts Awesome IRC Bot in the background
 * If you would like verbose output, run bot.php
 * instead
 */

passthru('clear');
error_reporting(0);
echo "Welcome to Awesome IRC Bot v2 Seriously Unstable Edition\n";
echo "Created by AwesomezGuy, follow @AwesomezGuy on Twitter\n\n";
echo "Backgrounding process...\n";
$pid = pcntl_fork();
if (!$pid)
	exec("php " . __DIR__ . "/bot.php &");
?>