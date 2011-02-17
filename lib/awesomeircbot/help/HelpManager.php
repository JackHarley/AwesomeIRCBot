<?php
/**
 * HelpManager Class
 * Stores help information for modules and
 * allows module configs to register help
 * articles
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */

namespace awesomeircbot\help;

use awesomeircbot\help\Help;

class HelpManager {
	
	protected static $help = array();
	
	public static function getDescription($command, $subcommand=false) {
		
		if ($subcommand)
			return static::$help[$command][$subcommand]->description;
		else
			return static::$help[$command]["BASE"]->description;
	}
	
	public static function getParameters($command, $subcommand=false) {
		
		if ($subcommand)
			return static::$help[$command][$subcommand]->parameters;
		else
			return static::$help[$command]["BASE"]->parameters;
	}
	
	public static function getSubcommands($command) {
	
		$subcommands = array();
		foreach(static::$help[$command] as $subcommand => $subcommandData)
			$subcommands[] = $subcommand;
		return $subcommands;
	}
	
	/**
	 * Registers a command which subcommands can register
	 * under
	 * .harass
	 *
	 * @param string the command name to register
	 *		     e.g. harass
	 * @param string command description (what it does)
	 * @param string parameters (if applicable), in this format:
	 *		     <nickname> (signifies a required value)
	 *		     [<description>] (signifies an optional value)
	 *		     e.g. <nickname> <username> <real name> [<description of yourself>]
	 */
	public static function registerCommand($command, $description, $parameters) {
		
		if (!static::$help[$command])
			static::$help[$command] = array();
		
		static::$help[$command]["BASE"] = new Help($description, $parameters);
	}
	
	/**
	 * Registers a subcommand under a command
	 * .harass add
	 *
	 * @param string the command name to register under (must be
	 *		     previously registered)
	 *		     e.g. harass
	 * @param string the subcommand name to register
	 *		     e.g. add
	 * @param string command description (what it does)
	 * @param string parameters (if applicable), in this format:
	 *		     <nickname> (signifies a required value)
	 *		     [<description>] (signifies an optional value)
	 *		     e.g. <nickname> <username> <real name> [<description of yourself>]
	 */
	public static function registerSubcommand($command, $subcommand, $description, $parameters) {

			static::$help[$command][$subcommand] = new Help($description, $parameters);
	}
}
?>