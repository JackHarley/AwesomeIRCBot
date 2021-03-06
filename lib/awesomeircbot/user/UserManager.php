<?php
/**
 * User Manager
 * Tracks online users and their privileges,
 * server modes, identification status and
 * information
 *
 * Copyright © 2014, Jack P. Harley, jackpharley.com.
 * All Rights Reserved
 */

namespace awesomeircbot\user;

use awesomeircbot\server\Server;
use awesomeircbot\config\Config;

class UserManager {
	
	/**
	 * Associative array of online tracked users
	 * nick => user object
	 */
	public static $trackedUsers = array();
	
	private function __construct() {
	}
	
	/**
	 * Adds tracking for an online nick and stores
	 * the given user object for them, if their nick is on the
	 * privileged config list it also sends a WHOIS
	 *
	 * @param string online nickname
	 * @param object User object
	 */
	public static function store($nick, $userObject) {
		$configUsers = Config::getRequiredValue("users");
		foreach($configUsers as $privilegedUser => $level) {
			if ($nick == $privilegedUser) {
				if (!static::$trackedUsers[$nick]) {
					$server = Server::getInstance();
					$server->whois($nick);
				}
			}
		}
		
		static::$trackedUsers[$nick] = $userObject;
	}
	
	/**
	 * Gets the user object for the nick supplied
	 *
	 * @param string online nickname
	 * @return object User object
	 * @return object empty User object
	 */
	public static function get($nick) {
		if (static::$trackedUsers[$nick])
			return static::$trackedUsers[$nick];
		else
			return new User;
	}
	
	/**
	 * Clears any data for the nick supplied
	 *
	 * @param string nickname
	 */
	public static function remove($nick) {
		unset(static::$trackedUsers[$nick]);
	}
	
	/**
	 * Renames a user
	 *
	 * @param string original nick
	 * @param string new nick
	 * @param string new ident
	 * @param string new host
	 * @param string new name
	 */
	public static function rename($oldNick, $newNick=false, $newIdent=false, $newHost=false, $newName=false) {
		if (!isset(static::$trackedUsers[$oldNick]))
			return;

		$user = static::get($oldNick);

		if ($newNick)
			$user->nickname = $newNick;
		if ($newIdent)
			$user->ident = $newIdent;
		if ($newHost)
			$user->host = $newHost;
		if ($newName)
			$user->realName = $newName;

		static::remove($oldNick);

		static::store($user->nickname, $user);
	}
}
?>