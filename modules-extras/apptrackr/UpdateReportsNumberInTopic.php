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
		
		echo $responseCode = $working->code;
		$jsonDataBlock = $working->data;
		$signature = $working->signature;
		
		$dataBlock = json_decode($jsonDataBlock);
		$numberOfReportPages = $dataBlock->pages;
		
		if ($responseCode != "200")
			return;
			
		// Find out the number of moderated pages
		$request = array(
			'object' => 'Moderator',
			'action' => 'getModeratedPages',
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
		
		echo $responseCode = $working->code;
		$jsonDataBlock = $working->data;
		$signature = $working->signature;
		
		$dataBlock = json_decode($jsonDataBlock);
		$numberOfModeratedPages = $dataBlock->pages;
		
		if ($responseCode != "200")
			return;
			
		$server = Server::getInstance();
		
		$channel = ChannelManager::get(static::$channelTopicToUpdate);
		$currentTopic = $channel->topic;
		
		if (!$currentTopic)
			return;
		
		if ($numberOfReportPages == "1") {
			$newTopic = preg_replace("/([0-9]*) Page[s] of Reports/", "$numberOfReportPages Page of Reports", $currentTopic);
		}
		else {
			$newTopic = preg_replace("/([0-9]*) Page[s] of Reports/", "$numberOfReportPages Pages of Reports", $currentTopic);
		}
		
		if ($numberOfModeratedPages == "1") {
			$newTopic = preg_replace("/([0-9]*) Page[s] of Moderated Links/", "$numberOfModeratedPages Page of Moderated Links", $newTopic);
		}
		else {
			$newTopic = preg_replace("/([0-9]*) Page[s] of Moderated Links/", "$numberOfModeratedPages Pages of Moderated Links", $newTopic);
		}
		
		if ($newTopic == $currentTopic) {
			return;
		}
		
		$channel->topic = $newTopic;
		ChannelManager::store(static::$channelTopicToUpdate, $channel);
			
		$server->topic(static::$channelTopicToUpdate, $newTopic);

	}
}
?>