<?php
/**
 * Config class
 * Handles configuration
 *
 * Copyright (c) 2013, Jack Harley
 * All Rights Reserved
 */

namespace awesomeircbot\config;

use awesomeircbot\log\ErrorLog;
use awesomeircbot\log\ErrorCategories;

class Config {
	
	protected static $config = array();
	
	/**
	 * Sets a config value
	 * @param mixed the key for the config value
	 * @param mixed the data to store
	 * @param int optionally, spoof the unix timestamp this key was
	 * updated at
	 */
	public static function setValue($key, $data, $lastUpdatedTime=false) {
		self::$config[$key] = array();
		self::$config[$key]["data"] = $data;
		
		if (!$lastUpdatedTime)
			$lastUpdatedTime = time();
		
		self::$config[$key]["lastUpdated"] = $lastUpdatedTime;
	}
	
	/**
	 * Gets a config value, or returns false if it does not exist
	 * @param mixed the key for the config value
	 * @return mixed either the data, or false if the key does not exist
	 */
	public static function getValue($key) {
		if (self::$config[$key]["data"])
			return self::$config[$key]["data"];
		else
			return false;
	}
	
	/**
	 * Gets a config value, or throws a fatal error if it does not exist
	 * @param mixed the key for the config value
	 * @return mixed the data
	 */
	public static function getRequiredValue($key) {
		if ($value = self::getValue($key))
			return $value;
		else {
			ErrorLog::log(ErrorCategories::FATAL, "Required config value '" . $key . "' is not set!");
		}
	}
	
	/**
	 * Prompts the user via STDIN for a config value, if the key has not been
	 * set
	 * @param mixed the key for the config value
	 * @param string the message to send to the user to prompt them for entry
	 * @param mixed optionally, a default data set if the user does not
	 * provide valid input
	 * @param boolean set this to false to make the value optional
	 */
	public static function promptForValueIfNotSet($key, $message, $default=false, $required=true) {
		if (self::$config[$key])
			return;
		else
			self::promptForValue($key, $message, $default);
	}
	
	/**
	 * Prompts the user via STDIN for a config value
	 * @param mixed the key for the config value
	 * @param string the message to send to the user to prompt them for entry
	 * @param mixed optionally, a default data set if the user does not
	 * provide valid input
	 * @param boolean set this to false to make the value optional
	 */
	public static function promptForValue($key, $message, $default=false, $required=true) {
		echo "\n";
		echo $message;
		echo "\n";
		
		$line = trim(fgets(STDIN));
		
		if ($line)
			self::setValue($key, $line);
		else {
			if ($default)
				self::setValue($key, $default);
			
			if ($required === false)
				return;
			
			else {
				while (true) {
					echo "\nInvalid input, please try again\n";
					$line = trim(fgets(STDIN));
					if ($line) {
						self::setValue($key, $line);
						return;
					}
				}
			}
		}
	}
	
	/**
	 * Prompts the user via STDIN for the first channel, if it has not been
	 * set
	 */
	public static function promptForFirstChannelIfNotSet() {
		if (self::$config["channels"])
			return;
		else
			self::promptForFirstChannel();
	}
	
	/**
	 * Prompts the user via STDIN for the first channel to connect to
	 */
	public static function promptForFirstChannel() {
		echo "\nPlease enter the first channel to connect to (e.g. #chatulous)\n";
		$line = trim(fgets(STDIN));
		
		if (!$line) {
			while (true) {
				echo "\nInvalid input, please try again\n";
				$line = trim(fgets(STDIN));
				
				if ($line) {
					break;
				}
			}
		}
		
		$channels = array();
		$channels[] = $line;
		self::setValue("channels", $channels);
	}
	
	/**
	 * Prompts the user via STDIN for the first priveleged user,
	 * if it has not been set
	 */
	public static function promptForFirstPrivilegedUserIfNotSet() {
		if (self::$config["users"])
			return;
		else
			self::promptForFirstPrivilegedUser();
	}
	
	/**
	 * Prompts the user via STDIN for the first privileged user
	 */
	public static function promptForFirstPrivilegedUser() {
		echo "\nPlease enter the nickname of the main administrator, this user will need to be registered and identified with NickServ before the bot will give them privileges (e.g. Naikcaj)\n";
		$line = trim(fgets(STDIN));
		
		if (!$line) {
			while (true) {
				echo "\nInvalid input, please try again\n";
				$line = trim(fgets(STDIN));
				
				if ($line) {
					break;
				}
			}
		}
		
		$configUsers = array();
		$configUsers[$line] = 10;
		self::setValue("users", $configUsers);
	}
	
	/**
	 * Initializes the required config values if they have not been set
	 */
	public static function initializeRequiredValues() {
		
		// server settings
		self::promptForValueIfNotSet("serverAddress", "Please enter a server address to connect to (e.g. irc.rizon.net)");
		self::promptForValueIfNotSet("serverPort", "Please enter the port to connect to (e.g. 6667)");
		self::promptForValueIfNotSet("username", "Please enter the IDENT/username to use to connect (e.g. bot)");
		self::promptForValueIfNotSet("nickname", "Please enter the nickname to use to connect (e.g. AwesomeBot)");
		self::promptForValueIfNotSet("realName", "Please enter the real name to use to connect (e.g. Awesome Bot)");
		self::promptForValueIfNotSet("nickservPassword", "Optionally, enter the nickserv password to identify with, otherwise just hit enter (e.g. qwertyuiop)", false, false);
		
		// privileged user
		self::promptForFirstPrivilegedUserIfNotSet();
		
		// channel
		self::promptForFirstChannelIfNotSet();
		
		// miscellaneous
		self::promptForValueIfNotSet("commandCharacter", "Please enter the character to prefix commands with (e.g. !)");
		self::promptForValueIfNotSet("notificationType", "Please enter the type of notification to use, 'notice' or 'pm' (e.g. notice)");
		
		self::setValue("verboseOutput", 30); // everything except debug messages
	}
	
	/**
	 * Checks if there is a config value newer than the supplied time
	 *
	 * @param string the key under which the value was stored
	 * @param int the unix timestamp to check against
	 * @return boolean true if key exists newer than the given timestamp,
	 * otherwise, false
	 */
	public static function checkIfValueExistsAndIsNewerThan($key, $time) {
		if (self::$config[$key]["lastUpdated"] > $time)
			return true;
		else
			return false;
	}
	
	/**
	 * Gets the last updated time for the specified config key
	 *
	 * @param string the key under which the value was stored
	 * @return int unix timestamp of last update to value or boolean false
	 * if key does not exist
	 */
	public static function getLastUpdatedTime($key) {
		if (self::$config[$key]["lastUpdated"])
		 	return self::$config[$key]["lastUpdated"];
		 else
		 	return false;
	}
	
	/**
	 * Gets all the values and returns it as an associative array
	 *
	 * @return array associative array of values
	 */
	public static function getAllValues() {
		return self::$config;
	}
	
	/**
	 * Changes all the timestamps to now
	 */
	public static function changeAllTimestampsToNow() {
		$allKeys = self::getAllValues();
		
		foreach($allKeys as $key => $types) {
			self::$config[$key]["lastUpdated"] = time();
		}
	}
}