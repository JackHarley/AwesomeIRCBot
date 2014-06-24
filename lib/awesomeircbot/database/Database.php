<?php
/**
 * Database Class
 * Singleton for PDO
 *
 * Copyright (c) 2013, Jack Harley
 * All Rights Reserved
 */

namespace awesomeircbot\database;

use awesomeircbot\config\Config;

use awesomeircbot\channel\ChannelManager;
use awesomeircbot\data\DataManager;
use awesomeircbot\user\UserManager;

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
	    global $host, $port, $databaseName, $username, $password;

	    try {
            $this->pdo = new \PDO("mysql:host=" . $host . ";port=" . $port . ";dbname=" . $databaseName, $username, $password);
	    }
	    catch (\Exception $e) {
		    die("\nFailed to connect to your MySQL database, check your config.php and ensure the values are correct then try again\n");
	    }

		$this->query("
			CREATE TABLE IF NOT EXISTS `channels` (
				`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				`name` varchar(48) NOT NULL,
				`topic` text NOT NULL,
				`modes` text NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;"
		);
		
		$this->query("
			CREATE TABLE IF NOT EXISTS `channel_users` (
				`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				`nickname` varchar(48) NOT NULL,
				`channel_name` varchar(48) NOT NULL,
				`privilege` varchar(255) NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;"
		);

	    $this->query("
			CREATE TABLE IF NOT EXISTS `users` (
				`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				`nickname` varchar(32) NOT NULL,
				`ident` varchar(32) NOT NULL,
				`host` varchar(255) NOT NULL,
				`real_name` varchar(255) NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;"
	    );
		
		$this->query("
			CREATE TABLE IF NOT EXISTS `channel_actions` (
				`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				`type` smallint unsigned NOT NULL,
				`nickname` varchar(48) NOT NULL,
				`host` varchar(128) NOT NULL,
				`ident` varchar(24) NOT NULL,
				`message` text NOT NULL,
				`target_nick` varchar(48) NOT NULL,
				`channel_name` varchar(48) NOT NULL,
				`time` bigint(20) unsigned NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;"
		);

	    $this->query("
			CREATE TABLE IF NOT EXISTS `module_data` (
				`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				`module` varchar(128) NOT NULL,
				`title` varchar(32) NOT NULL,
				`data` mediumtext NOT NULL,
				`last_updated_time` bigint(20) unsigned NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;"
	    );

	    $this->query("
			CREATE TABLE IF NOT EXISTS `config` (
				`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				`name` varchar(48) NOT NULL,
				`data` mediumtext NOT NULL,
				`last_updated_time` bigint(20) unsigned NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;"
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
			
		// Module data
		$stmt = $this->prepare("SELECT * FROM module_data");
		$stmt->execute();
		
		while($row = $stmt->fetchObject()) {
			if (!DataManager::checkIfDataExistsAndIsNewerThan($row->title, $row->module, $row->last_updated_time)) {
				
				$data = unserialize($row->data);
				DataManager::store($row->title, $data, $row->module, $row->last_updated_time);
			}
		}
		
		// Config
		$stmt = $this->prepare("SELECT * FROM config");
		$stmt->execute();
		
		while($row = $stmt->fetchObject()) {
			if (!Config::checkIfValueExistsAndIsNewerThan($row->name, $row->last_updated_time)) {
				Config::setValue($row->name, unserialize($row->data), $row->last_updated_time);
			}
		}
	}
		
	/**
	 * Updates the database with data from the script
	 * arrays
	 */
	public function updateDatabase() {
		
		// Channels
		$this->query("DELETE FROM channel_users");
		$this->query("DELETE FROM channels");
		
		foreach(ChannelManager::$connectedChannels as $channel) {
			$stmt = $this->pdo->prepare("INSERT INTO channels(name, topic) VALUES(?,?);");
			$stmt->execute(array($channel->channelName, $channel->topic));
			
			foreach($channel->connectedNicks as $connectedNick) {
				if (isset($channel->privilegedNicks[$connectedNick])) {
					$stmt = $this->pdo->prepare("INSERT INTO channel_users(nickname, channel_name, privilege) VALUES(?,?,?);");
					$stmt->execute(array($connectedNick, $channel->channelName, $channel->privilegedNicks[$connectedNick]));
				}
				else {
					$stmt = $this->pdo->prepare("INSERT INTO channel_users(nickname, channel_name) VALUES(?,?);");
					$stmt->execute(array($connectedNick, $channel->channelName));
				}
			}
		}

		// Users
		$this->query("DELETE FROM users");

		foreach(UserManager::$trackedUsers as $user) {
			if (($user->nickname) && ($user->ident) && ($user->host) && ($user->realName)) {
				$stmt = $this->prepare("INSERT INTO users(nickname, ident, host, real_name) VALUES(?,?,?,?);");
				$stmt->execute(array($user->nickname, $user->ident, $user->host, $user->realName));
			}
			else if (($user->nickname) && ($user->ident) && ($user->host)) {
				$stmt = $this->prepare("INSERT INTO users(nickname, ident, host) VALUES(?,?,?);");
				$stmt->execute(array($user->nickname, $user->ident, $user->host));
			}
			else if ($user->nickname) {
				$stmt = $this->prepare("INSERT INTO users(nickname) VALUES(?);");
				$stmt->execute(array($user->nickname));
			}
		}

		// Module data
		
		// check if we need to update anything currently in the db
		$stmt = $this->prepare("SELECT * FROM module_data");
		$stmt->execute();
		
		$doneTitles = array();
		while($row = $stmt->fetchObject()) {
			if (DataManager::checkIfDataExistsAndIsNewerThan($row->title, $row->module, $row->last_updated_time)) {
				
				$data = DataManager::retrieve($row->title, $row->module);
				$dbData = serialize($data);
				$time = DataManager::getLastUpdatedTime($row->title, $row->module);
				
				$stmt = $this->prepare("DELETE FROM module_data WHERE title=?;");
				$stmt->execute(array($row->title));
				
				$stmt = $this->prepare("INSERT INTO module_data(title, data, module, last_updated_time) VALUES(?,?,?,?);");
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
				
				$stmt = $this->prepare("DELETE FROM module_data WHERE title=?;");
				$stmt->execute(array($title));
				
				$stmt = $this->prepare("INSERT INTO module_data(title, data, module, last_updated_time) VALUES(?,?,?,?);");
				$stmt->execute(array($title, $dbData, $module, $types["lastUpdated"]));
			}
		}
		
		// Config
		
		// check if we need to update anything currently in the db
		$stmt = $this->prepare("SELECT * FROM config");
		$stmt->execute();
		
		$doneKeys = array();
		while($row = $stmt->fetchObject()) {
			if (Config::checkIfValueExistsAndIsNewerThan($row->name, $row->last_updated_time)) {
				
				$data = Config::getValue($row->name);
				$dbData = serialize($data);
				$time = Config::getLastUpdatedTime($row->name);
				
				$stmt = $this->prepare("DELETE FROM config WHERE name=?;");
				$stmt->execute(array($row->name));
				
				$stmt = $this->prepare("INSERT INTO config(name, data, last_updated_time) VALUES(?,?,?);");
				$stmt->execute(array($row->name, $dbData, $time));
			}
			$doneKeys[$row->name] = true;
		}
		
		// do everything else
		$allKeys = Config::getAllValues();
		
		foreach($allKeys as $name => $types) {
			
			if ($doneKeys[$name])
				continue;
			
			$dbData = serialize($types["data"]);
			
			$stmt = $this->prepare("DELETE FROM config WHERE name=?;");
			$stmt->execute(array($name));
				
			$stmt = $this->prepare("INSERT INTO config (name, data, last_updated_time) VALUES (?,?,?);");
			$stmt->execute(array($name, $dbData, $types["lastUpdated"]));
		}
	}
}
?>