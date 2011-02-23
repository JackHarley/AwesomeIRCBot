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
	public $connectedNicks = array();
	
	/**
	 * An associative array of nicknames
	 * and the privileges they hold in the
	 * channel
	 * nick => privilege character (~, &, @, %, +)
	 */
	public $privilegedNicks = array();
	
	/**
	 * Construction
	 *
	 * @param string channel name
	 */
	public function __construct($channel) {
		$this->channelName = $channel;
	}
	
	/**
	 * Adds a nickname to the array of connected
	 * nicknames, checking if it exists first
	 *
	 * @param string nickname to add
	 * @param string privilege character (~, &, @, %, +)
	 */
	public function addConnectedNick($nick, $privileges=false) {
		if (in_array($nick, $this->connectedNicks) === false) {
			$this->connectedNicks[] = $nick;
		
			if ($privileges)
				$this->privilegedNicks[$nick] = $privileges;
		}
	}
	
	/**
	 * Removes a nickname from the array
	 * of connected nicknames
	 *
	 * @param string nickname to remove
	 */
	public function removeConnectedNick($nick) {
		foreach($this->connectedNicks as $id => $connectedNick) {
			if ($connectedNick == $nick) {
				unset($this->connectedNicks[$id]);
			}
		}
		
		unset($this->privilegedNicks[$nick]);
	}
	
	/**
	 * Checks if a nickname is connected to the
	 * channel
	 *
	 * @param string nickname
	 * @return boolean depending on whether or not user is connected
	 */
	public function isConnected($nick) {
		if (in_array($nick, $this->connectedNicks))
			return true;
		else
			return false;
	}
}
?>