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
	
	public static function runCommand($command, $message, $nick) {
		
		$module = static::$mappedCommands[$command];
		if (!$module)
			return 1;
			
		$moduleInstance = new $module($message, $nick);
		if ($moduleInstance->run())
			return true;
		else
			return 2;
	}
	
	public static function mapCommand($trigger, $module) {
		static::$mappedCommands[$trigger] = $module;
	}
	
	public static function loadModuleConfig($moduleConfig) {
		foreach($moduleConfig::$mappedCommands as $command => $module)
			static::mapCommand($command, $module);
	}
	
	public static function runEvent($eventType, $line, $senderNick=false, $targetNick=false) {
		
		$module = static::$mappedEvents[$eventType];
		if (!$module)
			return 1;
			
		$moduleInstance = new $module($line, $senderNick, $eventType, $targetNick);
		if ($moduleInstance->run())
			return true;
		else
			return 2;
	}
	
	public static function mapEvent($event, $module){
		static::$mappedEvents[$event] = $module;
	}
}