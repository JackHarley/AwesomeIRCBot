<?php
namespace modules;
use awesomeircbot\module\Module;
use awesomeircbot\server\Server;

class QuitFromServer extends Module {
	
	public function run() {
		$server = Server::getInstance();
		$server->message($runNick, "Shutting down...");
	}
}