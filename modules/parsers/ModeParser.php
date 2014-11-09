<?php
/**
 * ModeParser Module
 * Deals with channel modes and privileges
 *
 * NOTE- THIS IS A SYSTEM MODULE, REMOVING IT MAY
 * 	   REMOVE VITAL FUNCTIONALITY FROM THE BOT
 *
 * Copyright © 2014, Jack P. Harley, jackpharley.com.
 * All Rights Reserved
 */
namespace modules\parsers;

use awesomeircbot\module\Module;
use awesomeircbot\channel\ChannelManager;
use awesomeircbot\line\ReceivedLine;

class ModeParser extends Module {

	public static $requiredUserLevel = 0;

	public static $modesWithTarget = ["+b", "-b", "+k", "-k", "+l", "+o", "-o", "+h", "-h", "+v",
			"-v", "+d", "+e", "-e", "+f", "+F", "+g", "-g", "+H", "+I", "-I", "+J", "+j", "+L",
			"+w", "-w", "+X", "-X"];

	public function run() {
		$channel = ChannelManager::get($this->channel);

		$line = new ReceivedLine($this->runMessage);
		$line->parse();

		// We currently only process modes with targets. We can therefor skip if no targets present.
		if (empty($line->targetNick))
			return;

		$modes = array();
		if (strlen($line->message) > 2) {
			$split = str_split($line->message);
			$symbol = "";
			foreach ($split as $id => $character) {
				if ($character == "+" || $character == "-")
					$symbol = $character;
				else
					$modes[] = $symbol . $character;
			}
		}
		else {
			$modes[] = $line->message;
		}
		$targetIndex = 0;
		foreach ($modes as $mode) {

			switch ($mode) {
				case "+v":
					$channel->addPrivilege($this->getTargetNick($line, $targetIndex++), "+");
				break;
				case "+h":
					$channel->addPrivilege($this->getTargetNick($line, $targetIndex++), "%");
				break;
				case "+o":
					$channel->addPrivilege($this->getTargetNick($line, $targetIndex++), "@");
				break;
				case "+a":
					$channel->addPrivilege($this->getTargetNick($line, $targetIndex++), "&");
				break;
				case "+q":
					$channel->addPrivilege($this->getTargetNick($line, $targetIndex++), "~");
				break;
				default:
					if (in_array($mode, static::$modesWithTarget))
						$targetIndex++;
				break;
			}
		}

		ChannelManager::store($this->channel, $channel);
	}

	private function getTargetNick($line, $targetIndex) {
		if (count($line->targetNick) < ($targetIndex+1)) {
			$targetIndex = 0;
		}
		return $line->targetNick[$targetIndex];
	}
}
?>