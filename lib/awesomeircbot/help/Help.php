<?php
/**
 * Help class
 * Used for a command or subcommand entry
 * into the help system
 *
 * Copyright © 2014, Jack P. Harley, jackpharley.com.
 * All Rights Reserved
 */

namespace awesomeircbot\help;

class Help {
	
	// Description
	public $description;
	
	/**
	 * Parameters
	 * <required> [<optional>]
	 */
	public $parameters;
	
	/**
	 * Construction
	 *
	 * @param string description
	 * @param string parameters
	 */
	public function __construct($description, $parameters) {
		$this->description = $description;
		$this->parameters = $parameters;
	}
}
?>