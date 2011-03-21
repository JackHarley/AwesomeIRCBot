<?php
/**
 * Database Class
 * Singleton for PDO
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */

namespace awesomeircbot\database;

use config\Config;

use awesomeircbot\channel\ChannelManager;

class Database {
	
    protected static $instance;
    protected $pdo;

    protected function __construct() {
        $this->pdo = new \PDO("sqlite:" . __DIR__ . "/../../../database/database.sqlite");
		
		$this->pdo->query("
			CREATE TABLE IF NOT EXISTS privileged_users (
				nickname TEXT,
				level INTEGER
			);"
		);
		
		$this->pdo->query("
			CREATE TABLE IF NOT EXISTS channels (
				name TEXT,
				topic TEXT,
				modes TEXT
			);"
		);
		
		$this->pdo->query("
			CREATE TABLE IF NOT EXISTS channel_users (
				nickname TEXT,
				channel_name TEXT,
				privilege TEXT
			);"
		);
    }

    public static function getInstance() {
        if (!static::$instance)
            static::$instance = new Database();
        return static::$instance;
    }

    public function __call($method, $args) {
        return call_user_func_array(array($this->pdo, $method), $args);
    }
	
	public function updateScriptArrays() {
		
		// Users
		$stmt = $this->pdo->prepare("SELECT * FROM privileged_users");
		$stmt->execute();
		
		while ($row = $stmt->fetchObject())
			Config::$users[$row->nickname] = $row->level;
	}
		
	public function updateDatabase() {
		
		// Privileged Users
		$stmt = $this->pdo->prepare("DELETE FROM privileged_users;");
		$stmt->execute();
		
		foreach (Config::$users as $user => $level) {
			$stmt = $this->pdo->prepare("INSERT INTO privileged_users(nickname, level) VALUES(?,?);");
			$stmt->execute(array($user, $level));
		}
		
		// Channels
		$stmt = $this->pdo->prepare("DELETE FROM channels;DELETE FROM channel_users");
		$stmt->execute();
		
		foreach(ChannelManager::$connectedChannels as $channel) {
			$stmt = $this->pdo->prepare("INSERT INTO channels(name) VALUES(?);");
			$stmt->execute(array($channel->channelName));
			
			foreach($channel->connectedNicks as $connectedNick) {
				$stmt = $this->pdo->prepare("INSERT INTO channel_users(nickname, channel_name, privilege) VALUES(?,?,?);");
				$stmt->execute(array($connectedNick, $channel->channelName, $channel->privilegedNicks[$connectedNick]));
			}
		}	
	}
}
?>