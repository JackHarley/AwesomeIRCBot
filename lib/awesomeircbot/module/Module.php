<?php
/**
 * Module class
 * Should be extended by any module for the bot
 */

namespace awesomeircbot\module;

class Module {
	
	public $returnLine = false;
	
	public $runLine;
	public $runNick;
	public $runUser;
	
	public function __construct($runParams, $runNick, $runUser) {
		$this->runParams = $runParams;
		$this->runNick = $runNick;
		$this->runUser = $runUser;
	}
}
?>