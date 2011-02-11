<?php
/**
 * Module Config interface
 * Should be implemented by any module configs
 * which developers use
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */

namespace awesomeircbot\module;

interface ModuleConfig {
	
	public static $mappedCommands;
	public static $mappedEvents;
	public static $mappedTriggers;
}
?>