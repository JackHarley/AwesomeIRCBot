<?php
/**
 * Shortens a URL using the is.gd service
 *
 * Copyright © 2014, Jack P. Harley, jackpharley.com.
 * All Rights Reserved
 *
 * @param string $url url to shorten
 * @return string shortned url
 */
function shortenURL($url) {
	return file_get_contents("http://is.gd/create.php?format=simple&url=" . urlencode($url));
}