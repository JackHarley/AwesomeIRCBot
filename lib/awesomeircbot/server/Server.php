<?php
/**
 * Server class
 * Includes all the necessary functions to use an IRC server
 * e.g. ->message(), ->join(), ->quit()
 *
 * Copyright (c) 2013, Jack Harley
 * All Rights Reserved
 */

namespace awesomeircbot\server;
use awesomeircbot\config\Config;
use awesomeircbot\channel\ChannelManager;
use awesomeircbot\log\ErrorCategories;
use awesomeircbot\log\ErrorLog;
use awesomeircbot\database\Database;
use awesomeircbot\line\ReceivedLineTypes;
use awesomeircbot\user\UserManager;

class Server {
	
	protected static $instance;
	
	public static $serverHandle;

	public static $quit;
	
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
		ErrorLog::log(ErrorCategories::NOTICE, "Opening socket to server at " . Config::getRequiredValue("serverAddress") . ":" . Config::getRequiredValue("serverPort"));
		static::$serverHandle = fsockopen(Config::getRequiredValue("serverAddress"), Config::getRequiredValue("serverPort"));
		
		// Check if it worked
		if (!static::$serverHandle) {
			ErrorLog::log(ErrorCategories::FATAL, "Attempt to establish connection failed!");
			return 1;
		}
		else {
			ErrorLog::log(ErrorCategories::NOTICE, "Socket connection to server established");
			return true;
		}
	}
	
	/**
	 * Check if the connection is still established to
	 * the server
	 *
	 * @return boolean depending on connection status
	 */
	public function connected() {
		if (static::$quit === true) {
			static::$quit = false;
			return false;
		}

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
		ErrorLog::log(ErrorCategories::NOTICE, "Authenticating with the server");
		fwrite(static::$serverHandle, "NICK " . Config::getRequiredValue("nickname") . "\n");
		fwrite(static::$serverHandle, "USER " . Config::getRequiredValue("username") . " 0 * :" . Config::getRequiredValue("realName") . "\n");
	}
	
	/**
	 * Identify to NickServ
	 */
	public function identifyWithNickServ() {
		// Send the identification message to the server
		ErrorLog::log(ErrorCategories::NOTICE, "Identifying with NickServ with password '" . Config::getRequiredValue("nickservPassword") ."'");
		$this->message("NickServ", "IDENTIFY " . Config::getRequiredValue("nickservPassword"));
	}
	
	/**
	 * Join a specified channel
	 *
	 * @param string channel name
	 */
	public function join($channel) {

		// Request an invite from ChanServ (in case we need one, it doesn't hurt to try), then sleep for 1
		$this->message("ChanServ", "INVITE" . " " . $channel);
		sleep(1);

		// Send to the server
		ErrorLog::log(ErrorCategories::NOTICE, "Joining IRC channel '" . $channel . "'");
		fwrite(static::$serverHandle, "JOIN " . $channel . "\n");
	}
	
	/**
	 * Part a specified channel
	 *
	 * @param string channel name
	 */
	public function part($channel) {
		
		// Send to the server
		fwrite(static::$serverHandle, "PART " . $channel . "\n");
		ErrorLog::log(ErrorCategories::NOTICE, "Parting IRC channel '" . $channel . "'");
		
		// Remove the channel from the ChannelManager
		ChannelManager::remove($channel);
	}
	
	/**
	 * Gets the next line from the server and
	 * returns it
	 *
	 * @return string IRC line
	 */
	public function getNextLine() {
		
		ErrorLog::log(ErrorCategories::DEBUG, "Getting next line from IRC server");
		
		// Check we're connected
		if ($this->connected()) {
			// Get and return the next line
			$return = fgets(static::$serverHandle, 1024);
			ErrorLog::log(ErrorCategories::DEBUG, "Received IRC line from server (" . $return . ")");
			return $return;
		}
		else
			// Not connected? Return false
			ErrorLog::log(ErrorCategories::ERROR, "No longer connected to server!");
			return false;
	}
	
	/**
	 * Sends the QUIT message to the server, closes
	 * the connection
	 */
	public function quit() {
		
		// Quit and disconnect
		fwrite(static::$serverHandle, "QUIT :Bye Bye!\n");
		fclose(static::$serverHandle);
		static::$quit = true;
		ErrorLog::log(ErrorCategories::NOTICE, "Server quit sent and socket closed");
	}
	
	/** 
	 * Messages the target user or channel with
	 * the passed text
	 */
	 public function message($target, $message) {
	 	
	 	// Send it
	 	ErrorLog::log(ErrorCategories::DEBUG, "Messaging '" . $target . "' with message '" . $message . "'");
	 	fwrite(static::$serverHandle, "PRIVMSG " . $target . " :" . $message . "\n");
		
		// Log it (if chan)
		if (strpos($target, "#") !== false) {
			$db = Database::getInstance();
			$stmt = $db->prepare("INSERT INTO channel_actions (type, nickname, ident, channel_name, message, time) VALUES (?,?,?,?,?,?)");
			$stmt->execute(array(ReceivedLineTypes::CHANMSG, Config::getRequiredValue("nickname"), Config::getRequiredValue("username"), $target, $message, time()));
		}
	 }
	 
	 /**
	  * Sends a notice to the given user, same syntax as the 
	  * message() method
	  */
	 public function notice($target, $message) {
	 	
	 	// Send it
	 	ErrorLog::log(ErrorCategories::DEBUG, "Noticing '" . $target . "' with message '" . $message . "'");
	 	fwrite(static::$serverHandle, "NOTICE " . $target . " :" . $message . "\n");
		
		// Log it (if chan)
		if (strpos($target, "#") !== false) {
			$db = Database::getInstance();
			$stmt = $db->prepare("INSERT INTO channel_actions (type, nickname, ident, channel_name, message, time) VALUES (?,?,?,?,?,?)");
			$stmt->execute(array(ReceivedLineTypes::CHANMSG, Config::getRequiredValue("nickname"), Config::getRequiredValue("username"), $target, $message, time()));
		}
	 }
	 
	 /**
	  * Notifies the given user about an action based
	  * on your config settings for notifications
	  */
	 public function notify($target, $message) {
	 	
	 	// Check config and pass it to the appropriate function
	 	ErrorLog::log(ErrorCategories::DEBUG, "Going to notify '" . $target . "' with message '" . $message . "'");
	 	if (Config::getRequiredValue("notificationType") == "pm")
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
	 	ErrorLog::log(ErrorCategories::DEBUG, "Messaging '" . $target . "' with message '" . $message . "' formatted as an ACTION (/me)");
	 	fwrite(static::$serverHandle, "PRIVMSG " . $target . " :" . chr(1) . "ACTION " . $message . chr(1) . "\n");
		
		// Log it (if chan)
		if (strpos($target, "#") !== false) {
			$db = Database::getInstance();
			$stmt = $db->prepare("INSERT INTO channel_actions (type, nickname, ident, channel_name, message, time) VALUES (?,?,?,?,?,?)");
			$stmt->execute(array(ReceivedLineTypes::CHANMSG, Config::getRequiredValue("nickname"), Config::getRequiredValue("username"), $target, "ACTION " . $message, time()));
		}
	 }
	 	
	 
	 /**
	  * Pongs the given server
	  */
	 public function pong($target) {
	 	
	 	// Send it
	 	ErrorLog::log(ErrorCategories::DEBUG, "Sending PONG to " . $target);
	 	fwrite(static::$serverHandle, "PONG " . $target . "\n");
	 }
	 
	 /**
	  * Sends a WHOIS query to the server for
	  * the nickname specified
	  *
	  * @param string nickname to whois
	  */
	 public function whois($nickname) {
	 	
	 	// Send it
	 	ErrorLog::log(ErrorCategories::DEBUG, "Sending WHOIS request for '" . $nickname . "'");
	 	fwrite(static::$serverHandle, "WHOIS " . $nickname . "\n");
	 }

	/**
	 * Sends a WHO query to the server for the specified
	 * mask
	 *
	 * @param string who query mask
	 */
	public function who($mask) {
		// Send it
		ErrorLog::log(ErrorCategories::DEBUG, "Sending WHOIS request for '" . $nickname . "'");
		fwrite(static::$serverHandle, "WHO " . $mask . "\n");
	}

	/**
	  * Updates a channel topic
	  *
	  * @param string channel
	  * @param string topic
	  * @param boolean true if you want the bot to update the topic
	  * via chanserv
	  */
	 public function topic($channel, $topic=false, $chanserv=false) {
	 	
		if ($topic) {
			if ($chanserv) {
				ErrorLog::log(ErrorCategories::DEBUG, "Changing topic for '" . $channel . "' to '" . $topic . "' via ChanServ");
				$this->message("ChanServ", "TOPIC " . $channel . " " . $topic);
			}
			else {
				ErrorLog::log(ErrorCategories::DEBUG, "Changing topic for '" . $channel . "' to '" . $topic . "'");
				fwrite(static::$serverHandle, "TOPIC " . $channel . " :" . $topic . "\n");
			}
		}
		else {
			ErrorLog::log(ErrorCategories::DEBUG, "Sending request to server for the current topic for '" . $channel . "'");
			fwrite(static::$serverHandle, "TOPIC " . $channel . "\n");
		}
	 }
	 
	 /**
	  * Invites the given user to the given channel
	  *
	  * @param string nickname to invite
	  * @param string channel name
	  */
	 public function channelInvite($nick, $channel) {
	 	ErrorLog::log(ErrorCategories::DEBUG, "Sending channel invite for '" . $channel . "' to '" . $nick . "'");
	 	fwrite(static::$serverHandle, "INVITE " . $nick . " " . $channel . "\n");
	 }

	 /**
	  * Sets or unsets the given mode on the given channel
	  *
	  * @param string channel name
	  * @param string mode letter (e.g. i)
	  * @param string parameter to put after the mode, for example in the case of a ban (+b) it will be a full hostname
	  * @param boolean value, true will + the mode, false will - it
	  */
	 public function channelMode($channel, $mode, $value, $parameter="") {
		if ($parameter != "")
			 $parameter = " " . $parameter;

	 	ErrorLog::log(ErrorCategories::DEBUG, "Setting mode " . $mode . $parameter . " on '" . $channel . "' to '" . $value . "'");

		if ($value == true)
			fwrite(static::$serverHandle, "MODE " . $channel . " +" . $mode . $parameter . "\n");
		else if ($value == false)
			fwrite(static::$serverHandle, "MODE " . $channel . " -" . $mode . $parameter . "\n");
	 }
	 
	 /**
	  * Kicks a user from a channel
	  *
	  * @param string channel name
	  * @param string nickname
	  * @param string kick reason (optional)
	  */
	 public function kick($channel, $nick, $reason="Bye!") {
		ErrorLog::log(ErrorCategories::DEBUG, "Kicking " . $nick . " from " . $channel . "for " . $reason);
		
		fwrite(static::$serverHandle, "KICK " . $channel . " " . $nick . " :" . $reason . "\n");
	}
	
	/**
	 * Bans a user from a channel
	 *
	 * @param string channel name
	 * @param string nickname
	 */
	public function ban($channel, $nick) {
		ErrorLog::log(ErrorCategories::DEBUG, "Banning " . $nick . " from " . $channel);
		
		$this->channelMode($channel, "b " . $nick . "!*@*", true);
	}
	
	/**
	 * Kickbans a user from a channel
	 *
	 * @param string channel name
	 * @param string nickname
	 */
	public function kickban($channel, $nick) {
		$this->ban($channel, $nick);
		$this->kick($channel, $nick);
	}
	
	/**
	 * Ops a user
	 *
	 * @param string channel name
	 * @param string nickname to op
	 */
	public function op($channel, $nick) {
		$this->channelMode($channel, "o " . $nick, true);
	}
	
	/**
	 * DeOps a user
	 *
	 * @param string channel name
	 * @param string nickname to deop
	 */
	public function deOp($channel, $nick) {
		$this->channelMode($channel, "o " . $nick, false);
	}
	
	/**
	 * HalfOps a user
	 *
	 * @param string channel name
	 * @param string nickname to halfop
	 */
	public function halfOp($channel, $nick) {
		$this->channelMode($channel, "h " . $nick, true);
	}
	
	/**
	 * DeHalfOps a user
	 *
	 * @param string channel name
	 * @param string nickname to dehalfop
	 */
	public function deHalfOp($channel, $nick) {
		$this->channelMode($channel, "h " . $nick, false);
	}

	/**
	 * Logs in to an oper account ("opers up")
	 */
	public function oper($user, $pass) {

		// Send it
		ErrorLog::log(ErrorCategories::NOTICE, "Opering up with username " . $user . " and password " . $pass);
		fwrite(static::$serverHandle, "OPER " . $user . " " . $pass . "\n");
	}

	/**
	 * Forces a nick change on a user (oper only)
	 */
	public function saNick($user, $newNick) {

		// Send it
		ErrorLog::log(ErrorCategories::DEBUG, "Forcing nick change on " . $user . " to " . $newNick);
		fwrite(static::$serverHandle, "SANICK " . $user . " " . $newNick . "\n");

		// Change user info in storage
		ChannelManager::rename($user, $newNick);
		UserManager::rename($user, $newNick);
	}

	/**
	 * Force joins a user to a channel (oper only)
	 */
	public function saJoin($user, $channel) {

		// Send it
		ErrorLog::log(ErrorCategories::DEBUG, "Forcing channel join on " . $user . " to " . $channel);
		fwrite(static::$serverHandle, "SAJOIN " . $user . " " . $channel . "\n");
	}

	/**
	 * Force sets a host on a user (oper only)
	 */
	public function chgHost($user, $host) {

		// Send it
		ErrorLog::log(ErrorCategories::DEBUG, "Force changing host of " . $user . " to " . $host);
		fwrite(static::$serverHandle, "CHGHOST " . $user . " " . $host . "\n");

		UserManager::rename($user, false, false, $host);
	}

	/**
	 * Force sets an ident on a user (oper only)
	 */
	public function chgIdent($user, $ident) {

		// Send it
		ErrorLog::log(ErrorCategories::DEBUG, "Force changing ident of " . $user . " to " . $ident);
		fwrite(static::$serverHandle, "CHGIDENT " . $user . " " . $ident . "\n");

		UserManager::rename($user, false, $ident);
	}

	/**
	 * Kills a user from the server (oper only)
	 */
	public function kill($user, $reason="Killed") {

		// Send it
		ErrorLog::log(ErrorCategories::DEBUG, "Killing user " . $user . " from server");
		fwrite(static::$serverHandle, "KILL " . $user . " " . $reason . "\n");
	}
}
	 