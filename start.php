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

// Fork
$pid = pcntl_fork();

/**
 * At this point there are now 2 process running, 
 * one with $pid and one without.
 * The one without $pid is the child, and we will now 
 * use it to initialize the main script.
 * The parent process will simply die peacefully
 */
if (!$pid)
	exec("php " . __DIR__ . "/bot.php &");
?>