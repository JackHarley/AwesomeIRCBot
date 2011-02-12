<?php
/**
 * Module manager
 * Modules register with this and can then be triggered
 * by a trigger line
 * The Module Manager will handle calling the module and
 * outputting the return
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */

namespace awesomeircbot\module;

use awesomeircbot\user\UserManager;
use config\Config;

class ModuleManager {
	
	/**
	 * Associative array of commands to modules
	 * e.g. quit => modules\QuitFromServer
	 */
	public static $mappedCommands = array();
	
	/**
	 * Associative array of event types to modules
	 * e.g. ReceivedLineTypes::PING => modules\Pong
	 */
	public static $mappedEvents = array();
	
	
	/**
	 * This is a static class, it should not be instantiated
	 */
	private function __construct() {
	}
	
	/**
	 * Run a user command
	 *
	 * @param string command
	 * @param string full message the user sent
	 * @param string nickname of the user who sent the command
	 * @param string channel the message was sent on
	 */
	public static function runCommand($command, $message, $nick, $channel) {
		
		$module = static::$mappedCommands[$command];
		if (!$module)
			return 1;
		
		if ($module::$requiredUserLevel) {
			$user = UserManager::get($nick);
			
			if (!$user->isIdentified) {
				return 1;
			}
			else {
				if ($module::$requiredUserLevel > Config::$users[$nick])
					return 1;
			}
		}
				
		$moduleInstance = new $module($message, $nick, $channel);
		
		$moduleInstance->run();
		return true;
	}
	
	/**
	 * Map a command to a module
	 *
	 * @param string command to activate upon
	 * @param string module full namespace to activate
	 */
	public static function mapCommand($command, $module) {
		static::$mappedCommands[$command] = $module;
	}
	
	/**
	 * Run an event
	 *
	 * @param integer numerical event type (see awesomeircbot\line\ReceivedLineTypes)
	 * @param string The full line which triggered the mapping
	 * @param string The nickname of the sender of the activating line
	 * @param string The target of the line, if applicable
	 */
	public static function runEvent($eventType, $line, $senderNick=false, $targetNick=false) {
		
		if (!static::$mappedEvents[$eventType])
			return 1;
		
		foreach(static::$mappedEvents[$eventType] as $mappedEvent) {
			$module = $mappedEvent;
			$moduleInstance = new $module($line, $senderNick, false, $eventType, $targetNick);
			$moduleInstance->run();
		}
		
		return true;
	}
	
	/**
	 * Map an event to a module
	 *
	 * @param string event numerical type to activate upon (see awesomeircbot\line\ReceivedLineTypes)
	 * @param string module full namespace to activate
	 */
	public static function mapEvent($event, $module){
		static::$mappedEvents[$event][] = $module;
	}
	
	/**
	 * Load a module config which contains multiple
	 * modules that need to be loaded
	 *
	 * @param string full namespace of the module config
	 */
	public static function loadModuleConfig($moduleConfig) {
		foreach($moduleConfig::$mappedCommands as $command => $module)
			static::mapCommand($command, $module);
			
		foreach($moduleConfig::$mappedEvents as $event => $module)
			static::mapEvent($event, $module);
	}
}