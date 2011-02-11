<?php
/**
 * Module class
 * Should be extended by any module for the bot
 *
 * Copyright (c) 2011, Jack Harley
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
	public function __construct($runMessage, $senderNick, $channel=false, $eventType=false, $targetMick=false) {
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
	 * @return string parameter
	 */
	public function parameters($parameter) {
		$parameters = explode(" ", $this->runMessage);
		return $parameters[$parameter];
	}
}
?>