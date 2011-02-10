<?php
/**
 * User Manager
 * Tracks online users and their privileges,
 * server modes, identification status and
 * information
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */

namespace awesomeircbot\user;

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
	 * the given user object for them
	 *
	 * @param string online nickname
	 * @param object User object
	 */
	public static function add($nick, $userObject) {
		static::$trackedUsers[$nick] = $userObject;
	}
	
	/**
	 * Gets the user object for the nick supplied
	 *
	 * @param string online nickname
	 * @return object User object
	 * @return boolean false if no user exists
	 */
	public static function get($nick) {
		if (static::$trackedUsers[$nick])
			return static::$trackedUsers[$nick];
		else
			return false;
	}
	
	/**
	 * Clears any data for the nick supplied
	 *
	 * @param string nicknamr
	 */
	public static function remove($nick) {
		unset(static::$trackedUsers[$nick]);
	}
}
?>