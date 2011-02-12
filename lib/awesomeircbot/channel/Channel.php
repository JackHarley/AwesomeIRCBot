<?php
/**
 * Channel Class
 * Class for an IRC channel
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */

namespace awesomeircbot\channel;

class Channel {
	
	/**
	 * Channel information including name,
	 * topic, modes, etc.
	 */
	public $channelName;
	public $topic;
	public $modes = array();
	
	/**
	 * Array of users that are
	 * connected to the channel
	 */
	public $connectedNicks;
	
	/**
	 * An associative array of nicknames
	 * and the privileges they hold in the
	 * channel
	 * nick => privilege character (~, &, @, %, +)
	 */
	public $privilegedNicks;
}
?>