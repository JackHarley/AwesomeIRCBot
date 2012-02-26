<?php
/**
 * Generate Web Password Module
 * Generates a password for the web GUI
 *
 * NOTE- THIS IS A SYSTEM MODULE, REMOVING IT MAY
 * 	   REMOVE VITAL FUNCTIONALITY FROM THE BOT
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */
namespace modules\system;

use awesomeircbot\module\Module;
use awesomeircbot\config\Config;
use awesomeircbot\server\Server;

function generatePassword($length=9, $strength=0) {
	$vowels = 'aeuy';
	$consonants = 'bdghjmnpqrstvz';
	
	if ($strength & 1) {
		$consonants .= 'BDGHJLMNPQRSTVWXZ';
	}
	if ($strength & 2) {
		$vowels .= "AEUY";
	}
	if ($strength & 4) {
		$consonants .= '23456789';
	}
	if ($strength & 8) {
		$consonants .= '@#$%';
	}
 
	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt = 0;
		} else {
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
}

class GenerateWebPassword extends Module {
	
	public static $requireIdentification = true;
	
	public function run() {
		
		$server = Server::getInstance();
		
		$userPasswords = Config::getValue("userPasswords");
		if (!$userPasswords)
			$userPasswords = array();
		
		$pass = generatePassword();
		$userPasswords[$this->senderNick] = md5($pass);
		Config::setValue("userPasswords", $userPasswords);
		
		$server->notify($this->senderNick, "Password for " . $this->senderNick . " set to " . $pass);
	}
}
?>