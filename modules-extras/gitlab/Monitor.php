<?php
/**
 * Monitor Module
 * Checks for new events in any projects part of the configured group
 *
 * Copyright © 2014, Jack P. Harley, jackpharley.com.
 * All Rights Reserved
 */
namespace modules\gitlab;

use awesomeircbot\module\Module;
use awesomeircbot\server\Server;
use awesomeircbot\data\DataManager;

require_once(__DIR__ . "/lib/URLShortener.php");
require_once(__DIR__ . "/config.php");

class Monitor extends Module {

	protected static $projects = array();
	protected static $users = array();

	public function run() {

		if (empty(static::$projects)) {
			$data = json_decode(file_get_contents(GITLAB_API_URL . "/v" . GITLAB_API_VERSION .
				"/groups/" . GITLAB_MONITOR_GROUP_ID . "?private_token=" . GITLAB_API_PRIVATE_TOKEN
			));

			foreach($data->projects as $project)
				static::$projects[$project->id] = $project;
		}

		$latestAnnouncedEventDates = DataManager::retrieve("latestAnnouncedEvents");

		if (empty($latestAnnouncedEventDates))
			$firstRun = true;

		$server = Server::getInstance();
		foreach(static::$projects as $project) {
			$events = json_decode(file_get_contents(GITLAB_API_URL . "/v" . GITLAB_API_VERSION .
				"/projects/" . $project->id . "/events?private_token=" . GITLAB_API_PRIVATE_TOKEN
			));

			$eventsToAnnounce = array();
			foreach($events as $event) {
				if ($event->created_at == $latestAnnouncedEventDates[$project->id])
					break;
				$eventsToAnnounce[] = $event;
			}

			if ($events[0]->created_at) {
				if ($events[0]->created_at == $latestAnnouncedEventDates[$project->id])
					continue;
				$latestAnnouncedEventDates[$project->id] = $events[0]->created_at;
			}

			krsort($eventsToAnnounce); // events are given to us newest first by default, we want to
			// announce from oldest events to newest; hence reverse sorting

			if (!isset($firstRun)) { // don't announce anything on first run, or there will be spam
				foreach($eventsToAnnounce as $event) {
					switch($event->target_type) {
						case "Issue":
							// TODO
							break;
						default:
							if (isset($event->data->commits) && (!empty($event->data->commits))) {
								foreach($event->data->commits as $commit) {
									$message = chr(2) . chr(3) . "9[COMMIT]" . chr(3) . " " . chr(3)
											. "13[" . $project->name . "]" . chr(3) . " " . chr(3)
											. "8[" . str_replace("refs/heads/" , "", $event->data->ref)
											. "] " . chr(3) . chr(2) . $commit->author->name . " - " .
											$commit->message . " " . shortenURL($commit->url);
									$server->message(GITLAB_ANNOUNCE_CHANNEL, $message);
								}
							}
					}
				}
			}
		}

		DataManager::store("latestAnnouncedEvents", $latestAnnouncedEventDates);
	}

	protected function getUser($id) {
		if (!self::$users[$id]) {
			self::$users[$id] = json_decode(file_get_contents(GITLAB_API_URL . "/v" .
					GITLAB_API_VERSION . "/users/" . $id . "?private_token=" .
					GITLAB_API_PRIVATE_TOKEN
			));
		}

		return self::$users[$id];
	}
}
?>