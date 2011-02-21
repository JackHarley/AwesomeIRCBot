##Apptrackr Modules

####Apptrackr is a database of links for cracked iPhone/iPad/iPod Touch applications.
####I have included this module set since I originally coded AwesomeBot version 1 for the IRC channel of Hackulous,
####which is the supporting site for Apptrackr.

Installation
----------------
1, Copy this directory (apptrackr) into your /modules folder
2, Add the following line to bot.php:
	ModuleManager::loadModuleConfig('modules\apptrackr\configs\Apptrackr');
3, Restart the bot

UnInstallation
----------------
1, Remove the line you inserted into bot.php which says
	ModuleManager::loadModuleConfig('modules\apptrackr\configs\Apptrackr');
2, Delete the apptrackr folder from /modules
3, Restart the bot