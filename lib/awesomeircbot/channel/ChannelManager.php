<?php
/**
 * Channel Manager
 * Tracks connected channels, their modes, users
 * topic and other things
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */

namespace awesomeircbot\channel;

use awesomeircbot\channel\Channel;

class ChannelManager {
	
	/**
	 * Associative array of connected channels
	 * channel name => channel object
	 */
	public static $connectedChannels = array();
	
	private function __construct() {
	}
	
	/**
	 * Adds tracking for a channel
	 *
	 * @param string channel name
	 * @param object Channel object
	 */
	public static function store($chan, $chanObject) {
		static::$connectedChannels[$chan] = $chanObject;
	}
	
	/**
	 * Gets the channel object for the
	 * channel name specified
	 *
	 * @param string channel name
	 * @return object Channel object
	 * @return object empty Channel object
	 */
	public static function get($chan) {
		if (static::$connectedChannels[$chan])
			return static::$connectedChannels[$chan];
		else
			return new Channel($chan);
	}
	
	/**
	 * Clears any data for the channel supplied
	 *
	 * @param string channel name
	 */
	public static function remove($chan) {
		unset(static::$connectedChannels[$chan]);
	}
}
?>