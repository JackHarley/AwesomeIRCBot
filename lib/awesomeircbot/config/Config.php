<?php
/**
 * Config class
 * Handles configuration
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */

namespace awesomeircbot\config;

use awesomeircbot\error\ErrorLog;
use awesomeircbot\error\ErrorCategories;

class Config {
	
	protected static $config = array();
	
	/**
	 * Sets a config value
	 * @param mixed the key for the config value
	 * @param mixed the data to store
	 */
	public function setValue($key, $data) {
		self::$config[$key] = $data;
	}
	
	/**
	 * Gets a config value, or returns false if it does not exist
	 * @param mixed the key for the config value
	 * @return mixed either the data, or false if the key does not exist
	 */
	public function getValue($key) {
		if (self::$config[$key])
			return self::$config[$key];
		else
			return false;
	}
	
	/**
	 * Gets a config value, or throws a fatal error if it does not exist
	 * @param mixed the key for the config value
	 * @return mixed the data
	 */
	public function getRequiredValue($key) {
		if (self::$config[$key])
			return self::$config[$key];
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
	public function promptForValueIfNotSet($key, $message, $default=false, $required=true) {
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
	public function promptForValue($key, $message, $default=false, $required=true) {
		echo "\n";
		echo $message;
		echo "\n";
		
		$line = trim(fgets(STDIN));
		
		if ($line)
			self::setValue($key, $line);
		else {
			if ($default)
				self::setValue($key, $default);
			else if (!$required) {
				return;
			}
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
	public function promptForFirstChannelIfNotSet() {
		if (self::$config["channels"])
			return;
		else
			self::promptForFirstChannel();
	}
	
	/**
	 * Prompts the user via STDIN for the first channel to connect to
	 */
	public function promptForFirstChannel() {
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
	public function promptForFirstPrivilegedUserIfNotSet() {
		if (self::$config["users"])
			return;
		else
			self::promptForFirstPrivilegedUser();
	}
	
	/**
	 * Prompts the user via STDIN for the first privileged user
	 */
	public function promptForFirstPrivilegedUser() {
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
	public function initializeRequiredValues() {
		
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
		
		echo "\nFinished configurating!\n\n";
		
		self::setValue("verboseOutput", 30);
	}
}