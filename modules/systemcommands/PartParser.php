<?php
/**
 * PartParser Module
 * Deals with removing users from the
 * channel list
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */
namespace modules\systemcommands;

use awesomeircbot\module\Module;
use awesomeircbot\server\Server;
use awesomeircbot\channel\ChannelManager;
use awesomeircbot\data\DataManager;

class JoinParser extends Module {
	
	public static $requiredUserLevel = 0;
	
	public function run() {
		$channel = ChannelManager::get($this->channel);
		$channel->removeConnectedNick($this->senderNick);
		ChannelManager::store($this->channel, $channel);
	}
}
?>