<?php
/**
 * Module manager
 * Modules register with this and can then be triggered
 * by a trigger line
 * The Module Manager will handle calling the module and
 * outputting the return
 */

namespace awesomeircbot\module;

class ModuleManager {
	
	/**
	 * Associative array of triggers to modules
	 * e.g. quit => modules\QuitFromServer
	 */
	public static $mappedModules = array();
	
	public static function run($trigger, $params, $nick, $user) {
		
		$module = static::$mappedModules[$trigger];
		if (!$module)
			return 1;
			
		$moduleInstance = new $module($params, $nick, $user);
		if ($moduleInstance->run())
			return true;
		else
			return 2;
	}
	
	public static function map($trigger, $module){
		static::$mappedModules[$trigger] = $module;
	}
}