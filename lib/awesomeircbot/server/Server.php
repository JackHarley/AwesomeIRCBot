<?php
/**
 * Server class
 * Includes all the necessary functions to use an IRC server
 * e.g. ->message(), ->join(), ->quit()
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */

namespace awesomeircbot\server;
use config\Config;

class Server {
	
	protected static $instance;
	
	public static $serverHandle;
	
	protected function __construct() {
	}
	
	/**
	 * Returns an instance of this Server singleton
	 *
	 * @return An instance of this Server
	 */
	public static function getInstance() {
		if (!isset(static::$instance))
			static::$instance = new Server();
		return static::$instance;
	}
	
	/**
	 * Connect to the server
	 *
	 * @return boolean true on success
	 * @return error code on failure
	 */
	public function connect() {
	
		// Establish a connection
		static::$serverHandle = fsockopen(Config::$serverAddress, Config::$serverPort);
		
		// Check if it worked
		if (!static::$serverHandle)
			return 1;
		else
			return true;
	}
	
	/**
	 * Check if the connection is still established to
	 * the server
	 *
	 * @return boolean depending on connection status
	 */
	public function connected() {
		if (!feof(static::$serverHandle))
			return true;
		else
			return false;
	}
	
	/**
	 * Identify to the server, should be
	 * run immediately after connecting
	 */
	public function identify() {
	
		// Send the identification messages to the server
		fwrite(static::$serverHandle, "NICK " . Config::$nickname . "\0\n");
		fwrite(static::$serverHandle, "USER " . Config::$username . " 0 * :" . Config::$realName . "\0\n");
	}
	
	/**
	 * Identify to NickServ
	 */
	public function identifyWithNickServ() {
	
		// Send the identification messages to the server
		fwrite(static::$serverHandle, "PRIVMSG NickServ :IDENTIFY " . Config::$nickservPassword . "\0\n");
	}
	
	/**
	 * Join a specified channel and register it
	 * with the channel manager
	 *
	 * @param string channel name
	 */
	public function join($channel) {
		
		// Send to the server
		fwrite(static::$serverHandle, "JOIN " . $channel . "\0\n");
	}
	
	/**
	 * Gets the next line from the server and
	 * returns it
	 *
	 * @return string IRC line
	 */
	public function getNextLine() {
		
		// Check we're connected
		if ($this->connected())
			// Get and return the next line
			return fgets(static::$serverHandle, 256);
		else
			// Not connected? Return false
			return false;
	}
	
	/**
	  * Sends the QUIT message to the server, closes
	  * the connection and then kills the script
	  */

	public function quit() {
		
		// Quit and disconnect
		fwrite(static::$serverHandle, "QUIT :Bye Bye!\0\n");
		fclose(static::$serverHandle);
		
		// Die
		die();
	}
	
	/** 
	 * Messages the target user or channel with
	 * the passed text
	 */
	 public function message($target, $message) {
	 	
	 	// Send it
	 	fwrite(static::$serverHandle, "PRIVMSG " . $target . " :" . $message . "\0\n");
	 }
	 
	 /**
	  * Sends a notice to the given user, same syntax as the 
	  * message() method
	  */
	 public function notice($target, $message) {
	 	
	 	// Send it
	 	fwrite(static::$serverHandle, "NOTICE " . $target . " :" . $message . "\0\n");
	 }
	 
	 /**
	  * Notifies the given user about an action based
	  * on your config settings for notifications
	  */
	 public function notify($target, $message) {
	 	
	 	// Check config and pass it to the appropriate function
	 	if (Config::$notificationType == "pm")
	 		$this->message($target, $message);
	 	else
	 		$this->notice($target, $message);
	 }
	 	
	 /**
	  * Act, (/me) a message, same syntax as the message()
	  * method
	  */
	 public function act($target, $message) {
	 	
	 	// Send it
	 	fwrite(static::$serverHandle, "PRIVMSG " . $target . " :" . chr(1) . "ACTION " . $message . chr(1) . "\0\n");
	 }
	 	
	 
	 /**
	  * Pongs the given server
	  */
	 public function pong($target) {
	 	
	 	// Send it
	 	fwrite(static::$serverHandle, "PONG " . $target . "\0\n");
	 }
	 
	 /**
	  * Sends a WHOIS query to the server for
	  * the nickname specified
	  *
	  * @param string nickname to whois
	  */
	 public function whois($nickname) {
	 	
	 	// Send it
	 	fwrite(static::$serverHandle, "WHOIS " . $nickname . "\0\n");
	 }
}
	 