<?php
/**
 * Module class
 * Should be extended by any module for the bot
 *
 * Copyright (c) 2013, Jack Harley
 * All Rights Reserved
 */

namespace awesomeircbot\module;

abstract class Module {
	
	public $runMessage;
	public $senderNick;
	public $channel;
	public $eventType;
	public $targetNick;
	
	
	/**
	 * Construction
	 *
	 * @param string The run message or line
	 * @param string The sender nick
	 * @param string The event type, in case of event based activation
	 * @param string The target of the action, if applicable
	 */
	public function __construct($runMessage, $senderNick, $channel=false, $eventType=false, $targetNick=false) {
		$this->runMessage = $runMessage;
		$this->senderNick = $senderNick;
		$this->channel = $channel;
		$this->eventType = $eventType;
		$this->targetNick = $targetNick;
	}
	
	/**
	 * Gets the number parameter given, separated by spaces
	 *
	 * @param integer number parameter to return
	 * @param boolean supply all parameters from the given number to
	 * the end of the message
	 * @return string parameter
	 */
	public function parameters($parameter, $continueToEnd=false) {
		if (!$continueToEnd)
			$parameters = explode(" ", trim($this->runMessage));
		else
			$parameters = explode(" ", trim($this->runMessage), $parameter+1);
			
		if ($parameters[$parameter])
			return $parameters[$parameter];
		else
			return false;
	}
}
?>