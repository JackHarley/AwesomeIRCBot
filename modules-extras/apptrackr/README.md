##Apptrackr Modules

####Apptrackr is a database of links for cracked iPhone/iPad/iPod Touch applications.
####I have included this module set since I originally coded AwesomeBot version 1 for the IRC channel of Hackulous,
####which is the supporting site for Apptrackr.

Installation
----------------
1, Copy ALL the files in this directory into your /modules folder
2, Copy Apptrackr.php from the configs folder in this directory to your /modules/configs folder
3, Add the following line to bot.php:
	ModuleManager::loadModuleConfig('modules\configs\Apptrackr');
4, Restart the bot

UnInstallation
----------------
1, Remove the line you inserted into bot.php which says
	ModuleManager::loadModuleConfig('modules\configs\Apptrackr');
2, Restart the bot