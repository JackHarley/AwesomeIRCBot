<?php
/**
 * Received Line Types Class
 * Contains constants for each different
 * type of IRC line which can be received
 *
 * Copyright (c) 2011, Jack Harley
 * All Rights Reserved
 */

namespace awesomeircbot\line;

class ReceivedLineTypes {
	
	/**
	 * The following are all constants corresponding
	 * to the IRC RFC 1459 decimal reference, without
	 * the decimals
	 * For example, in RFC 1459, Section 4.6.2 is PING,
	 * so PING = 462
	 */
	const PRIVMSG = 4411;
	const CHANMSG = 4412;
	const PING = 462;
}
?>
	