<?php
/**
 * Module Module (ironic)
 * Administrates loaded module configs
 *
 * NOTE- THIS IS A SYSTEM MODULE, REMOVING IT MAY
 * 	   REMOVE VITAL FUNCTIONALITY FROM THE BOT
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */
namespace modules\systemcommands;

use awesomeircbot\module\Module;
use awesomeircbot\module\ModuleManager;

use awesomeircbot\server\Server;

class ModuleControls extends Module {
	
	public static $requiredUserLevel = 10;
	
	public function run() {
		
		$action = $this->parameters(1);
		$server = Server::getInstance();
		
		switch ($action) {
			case "load":
				ModuleManager::loadModuleConfig($this->parameters(2));
				$server->notify($this->senderNick, "Action completed");
			break;
			
			case "unload":
				ModuleManager::unloadModuleConfig($this->parameters(2));
				$server->notify($this->senderNick, "Action completed");
			break;
			
			default:
				$folder = opendir(__DIR__ . "/..");
				$modulePacks = array();
				while (($file = readdir($folder)) !== false) {
					if (($file != ".") && ($file != "..") && ($file != "modules.inc.php"))
						$modulePacks[] = $file;
				}
				
				$server->message($this->senderNick, "************************************");
				$server->message($this->senderNick, "Module Pack Listing");
				$server->message($this->senderNick, " ");
				
				foreach ($modulePacks as $modulePack) {
					$modulePackLength = strlen($modulePack);
					$spacesToAdd = 25 - $modulePackLength;
					
					for($i=1;$i<$spacesToAdd;$i++) {
						$spaces .= " ";
					}
					$server->message($this->senderNick, $modulePack . $spaces . " - " . chr(2) . chr(3) . "3Loaded" . chr(3) . chr(2));
					unset($spaces);
				}
				
				$server->message($this->senderNick, "************************************");
			break;
				
		}
	}
}
?>