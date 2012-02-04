<?php
/**
 * DataManager class
 * Handles data which modules can store and retrieve
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */

namespace awesomeircbot\data;

class DataManager {
	
	protected static $data = array();
	
	/**
	 * Retrieves data for a module
	 * Retrieves under the identifier of the modules
	 * full namespace unless $module is provided
	 *
	 * @param string the identifier under which the data was stored
	 * @param string optionally, override the module name to access another
	 * module's data
	 */
	public function retrieve($request, $module=false) {
		 
		 $trace = debug_backtrace();
		 if (!$module)
		 	$module = $trace[1]['class'];
		 
		 $module = str_replace('\\', '*', $module);
		 
		 if (self::$data[$module][$request]["data"])
		 	return self::$data[$module][$request]["data"];
		 else
		 	return false;
	}
	
	/**
	 * Stores data under an identifier
	 * Stores under the identifier of the modules
	 * full namespace unless $module is provided
	 *
	 * @param string the identifier to store the data under
	 * @param mixed the data to store, supports objects, arrays, strings, etc.
	 * @param string optionally, override the module name to store in another
	 * module's space
	 * @param int optionally, spoof the time this data was updated
	 */
	public function store($id, $data, $module=false, $lastUpdated=false) {
		
		$trace = debug_backtrace();
		if (!$module)
			$module = $trace[1]['class'];
		
		$module = str_replace('\\', '*', $module);
		
		if (!$lastUpdated)
			$lastUpdated = time();
		
		if (!self::$data[$module])
			self::$data[$module] = array();
		
		self::$data[$module][$id] = array();
		self::$data[$module][$id]["data"] = $data;
		self::$data[$module][$id]["lastUpdated"] = $lastUpdated;
		
		return true;
	}
	
	/**
	 * Checks if there is stored data newer than a certain time
	 *
	 * @param string the identifier under which the data was stored
	 * @param string the module name (e.g. modules\funstuff\Slap)
	 * @param int the unix timestamp to check against
	 * @return boolean true if data exists newer than the given timestamp,
	 * otherwise, false
	 */
	public function checkIfDataExistsAndIsNewerThan($id, $module, $time) {
		$module = str_replace('\\', '*', $module);
		
		if (self::$data[$module][$id]["lastUpdated"] > $time)
			return true;
		else
			return false;
	}
	
	/**
	 * Gets the last updated time for the specified piece of data
	 *
	 * @param string the identifier under which the data was stored
	 * @param string the module name (e.g. modules\funstuff\Slap)
	 * @return int unix timestamp of last update to data or boolean false
	 * if data does not exist
	 */
	public function getLastUpdatedTime($id, $module) {
		$module = str_replace('\\', '*', $module);
		
		if (self::$data[$module][$id]["lastUpdated"])
		 	return self::$data[$module][$id]["lastUpdated"];
		 else
		 	return false;
	}
	
	/**
	 * Gets all the data and returns it as an associative array
	 *
	 * @return array associative array of data
	 */
	public function getAllData() {
		return self::$data;
	}
	
	/**
	 * Changes all the data timestamps to now
	 */
	public function changeAllTimestampsToNow() {
		$allModules = self::getAllData();
		
		foreach($allModules as $module => $titles) {
			foreach($titles as $title => $types) {
				self::$data[$module][$title]["lastUpdated"] = time();
			}
		}
	}
}
?>