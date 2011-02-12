<?php
/* Configuration file for Awesome IRC Bot
 * Rename this file to config.php and edit the options below
 */
namespace config;

class Config {
	
	/* Description/Name of the Server
	 * This will be used in the DB for logging
	 * and the verbose output to command line
	 */
	public static $serverName = "Tech 'nd Stuff";
	
	/* Connection details
	 * Make sure to fill these out correctly
	 * $serverAddress - Host of the IRC server, e.g. irc.rizon.net
	 * $serverPort - Port of the IRC server, SSL is NOT supported, 
	 * 6667 on nearly all networks
	 */
	public static $serverAddress = "irc.techndstuff.com";
	public static $serverPort = 6667;
	
	/* Bot details
	 * These will be used for identifying the
	 * bot to the server
	 * $nickname - Nickname for bot to use, e.g. AwesomeBot
	 * $username - Username/IDENT, can be any string, e.g. Awesome
	 * $realName - Real Name/Gecos, e.g. Awesome Bot
	 * $nickservPassword - If the bot's nick is register, then 
	 * this is the password for bot to identify with using /msg 
	 * NickServ IDENTIFY
	 */
	public static $nickname = "AwesomeBot";
	public static $username = "AwesomeBot";
	public static $realName = "Awesome Bot";
	public static $nickservPassword = "";
	
	/* Database details
	 * These will be used to connect to the MySQL DB for
	 * bot management
	 * $databaseHost - Database host
	 * $databasePort - Usually 3306
	 * $databaseUser - MySQL Username
	 * $databasePass - Password for the MySQL User
	 * $databaseName - Database name
	 */
	public static $databaseHost = "localhost";
	public static $databasePort = 3306;
	public static $databaseUser = "user";
	public static $databasePass = "pass";
	public static $databaseName = "database";
	
	/* Commands to execute on connect
	 * This should be an array of Line objects
	 * e.g. 
	 * $command1 = new Line("PRIVMSG #sup :Sup?");
	 * array($command1);
	 */
	public static $connectCommands = array();
	
	/* Channels to join
	 * This should be an array of channel
	 * names with the hash
	 * e.g. array("#Chat", "#Help");
	 */
	public static $channels = array("#poop");
	
	/* Bot customizations
	 * It is optional to edit these, they will
	 * work just fine the way they are
	 * $modulePrefix - Character to prefix module commands with
	 */
	public static $modulePrefix = ".";
	
	/* Change the below line from true to false
	 * to prove you have read the config
	 */
	public static $die = false;
}
?>