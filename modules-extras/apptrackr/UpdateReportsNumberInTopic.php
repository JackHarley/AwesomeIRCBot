<?php
/**
 * Update Reports Number In Topic Module
 * Updates the number of reports in the topic
 * Requires channel name as a static property
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */
namespace modules\apptrackr;

use awesomeircbot\module\Module;
use awesomeircbot\server\Server;
use awesomeircbot\channel\ChannelManager;

class UpdateReportsNumberInTopic extends Module {
	
	public static $requiredUserLevel = 10;
	public static $channelTopicToUpdate = "#LinkHunters";
	public static $userID = 0;
	public static $userPassword = "";
	
	public function run() {
		
		// Find out the number of reports
		$request = array(
			'object' => 'Moderator',
			'action' => 'getReportPages',
			'auth' => array(
				'id' => static::$userID,
				'passhash' => md5(static::$userPassword)
			)
		);
		
		$wrapper = array('request' => json_encode($request));
		$wrapper = urlencode(json_encode($wrapper));
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://api.apptrackr.org/');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'request=' . $wrapper);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$returnData = curl_exec($ch);
		curl_close($ch);
		
		$working = json_decode($returnData);
		
		$responseCode = $working->code;
		$jsonDataBlock = $working->data;
		$signature = $working->signature;
		
		$dataBlock = json_decode($jsonDataBlock);
		
		$numberOfPages = $dataBlock->pages;
		
		$server = Server::getInstance();
		
		$channel = ChannelManager::get(static::$channelTopicToUpdate);
		$currentTopic = $channel->topic;
		
		if (!$currentTopic)
			return;
		
		$newTopic = preg_replace("/:: ([0-9]*) Pages of Reports ::/", ":: $numberOfPages Pages of Reports ::", $currentTopic);
		$channel->topic = $newTopic;
		ChannelManager::store(static::$channelTopicToUpdate, $channel);
		
		$server->topic(static::$channelTopicToUpdate, $newTopic);

	}
}
?>