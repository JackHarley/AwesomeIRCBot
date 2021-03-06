<?php
/**
 * User Class
 * Class for an online IRC user
 *
 * Copyright © 2014, Jack P. Harley, jackpharley.com.
 * All Rights Reserved
 */

namespace awesomeircbot\user;

class User {
	
	/**
	 * User information including
	 * nickname, ident, host, server modes
	 * time at which they connected and the
	 * server the user is connected to
	 */
	public $nickname;
	public $ident;
	public $host;
	public $realName;
	public $isIdentified = false;
}
?>