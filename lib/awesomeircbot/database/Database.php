<?php
/**
 * Database Class
 * Singleton for PDO
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */

namespace awesomeircbot\database;

use awesomeircbot\config\Config;

use awesomeircbot\channel\ChannelManager;
use awesomeircbot\data\DataManager;

class Database {
	
    protected static $instance;
    protected $pdo;

	/**
	 * Construction method
	 *
	 * Connects to the database and attempts to create
	 * the tables if they have not been created
	 */
    protected function __construct() {
        $this->pdo = new \PDO("sqlite:" . __DIR__ . "/../../../database/database.sqlite");
		
		$this->pdo->query("
			CREATE TABLE IF NOT EXISTS privileged_users (
				id INTEGER PRIMARY KEY AUTOINCREMENT,
				nickname TEXT,
				level INTEGER
			);"
		);
		
		$this->pdo->query("
			CREATE TABLE IF NOT EXISTS channels (
				id INTEGER PRIMARY KEY AUTOINCREMENT,
				name TEXT,
				topic TEXT,
				modes TEXT
			);"
		);
		
		$this->pdo->query("
			CREATE TABLE IF NOT EXISTS channel_users (
				id INTEGER PRIMARY KEY AUTOINCREMENT,
				nickname TEXT,
				channel_name TEXT,
				privilege TEXT
			);"
		);
		
		$this->pdo->query("
			CREATE TABLE IF NOT EXISTS channel_actions (
				id INTEGER PRIMARY KEY AUTOINCREMENT,
				type INTEGER,
				nickname TEXT,
				host TEXT,
				ident TEXT,
				channel_name TEXT,
				message TEXT,
				target_nick TEXT,
				time INTEGER
			);"
		);
		
		$this->pdo->query("
			CREATE TABLE IF NOT EXISTS module_data (
				id INTEGER PRIMARY KEY AUTOINCREMENT,
				module TEXT,
				title TEXT,
				data TEXT,
				last_updated_time INTEGER 
			);"
		);
    }
	
	/**
	 * Returns an instance of this Database singleton
	 *
	 * @return An instance of this Database
	 */
    public static function getInstance() {
        if (!static::$instance)
            static::$instance = new Database();
        return static::$instance;
    }
	
	/**
	 * Redirects calls to the object to the PDO object
	 */
    public function __call($method, $args) {
        return call_user_func_array(array($this->pdo, $method), $args);
    }
	
	/**
	 * Updates the arrays in the script with data from
	 * the database
	 */
	public function updateScriptArrays() {
		
		// Users
		/*$stmt = $this->pdo->prepare("SELECT * FROM privileged_users");
		$stmt->execute();
		
		while ($row = $stmt->fetchObject())
			Config::$users[$row->nickname] = $row->level;*/
		//above needs to be updated to be compatible with constant updating
			
		// Module data
		$stmt = $this->pdo->prepare("SELECT * FROM module_data");
		$stmt->execute();
		
		while($row = $stmt->fetchObject()) {
			if (!DataManager::checkIfDataExistsAndIsNewerThan($row->title, $row->module, $row->last_updated_time)) {
				
				$data = unserialize($row->data);
				DataManager::store($row->title, $data, $row->module, $row->last_updated_time);
			}
		}
			
	}
		
	/**
	 * Updates the database with data from the script
	 * arrays
	 */
	public function updateDatabase() {
		
		// Privileged Users
		$stmt = $this->pdo->prepare("DELETE FROM privileged_users;");
		$stmt->execute();
		
		$configUsers = Config::getRequiredValue("users");
		foreach ($configUsers as $user => $level) {
			$stmt = $this->pdo->prepare("INSERT INTO privileged_users(nickname, level) VALUES(?,?);");
			$stmt->execute(array($user, $level));
		}
		
		// Channels
		$stmt = $this->pdo->prepare("DELETE FROM channel_users");
		$stmt->execute();
		
		$stmt = $this->pdo->prepare("DELETE FROM channels");
		$stmt->execute();
		
		foreach(ChannelManager::$connectedChannels as $channel) {
			$stmt = $this->pdo->prepare("INSERT INTO channels(name, topic) VALUES(?,?);");
			$stmt->execute(array($channel->channelName, $channel->topic));
			
			foreach($channel->connectedNicks as $connectedNick) {
				$stmt = $this->pdo->prepare("INSERT INTO channel_users(nickname, channel_name, privilege) VALUES(?,?,?);");
				$stmt->execute(array($connectedNick, $channel->channelName, $channel->privilegedNicks[$connectedNick]));
			}
		}
		
		// Module data
		
		// check if we need to update anything currently in the db
		$stmt = $this->pdo->prepare("SELECT * FROM module_data");
		$stmt->execute();
		
		$doneTitles = array();
		while($row = $stmt->fetchObject()) {
			if (DataManager::checkIfDataExistsAndIsNewerThan($row->title, $row->module, $row->last_updated_time)) {
				
				$data = DataManager::retrieve($row->title, $row->module);
				$dbData = serialize($data);
				$time = DataManager::getLastUpdatedTime($row->title, $row->data);
				
				$stmt = $this->pdo->prepare("DELETE FROM module_data WHERE title=?;");
				$stmt->execute(array($row->title));
				
				$stmt = $this->pdo->prepare("INSERT INTO module_data(title, data, module, last_updated_time) VALUES(?,?,?,?);");
				$stmt->execute(array($row->title, $dbData, $row->module, $time));
			}
			$doneTitles[$row->title] = true;
		}
		
		// do everything else
		$allModules = DataManager::getAllData();
		
		foreach($allModules as $module => $titles) {
			foreach($titles as $title => $types) {
				
				if ($doneTitles[$title])
					continue;
				
				$dbData = serialize($types["data"]);
				
				$stmt = $this->pdo->prepare("INSERT INTO module_data(title, data, module, last_updated_time) VALUES(?,?,?,?);");
				$stmt->execute(array($title, $dbData, $module, $types["lastUpdated"]));
			}
		}
	}
}
?>