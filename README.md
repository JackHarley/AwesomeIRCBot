# Awesome IRC Bot Framework v2
#### Powerful, User Friendly PHP IRC Bot Framework
#### Created by AwesomezGuy
#### Follow me on [Twitter](http://twitter.com/AwesomezGuy)

Introduction
-------------
Awesome IRC Bot v2 is a powerful framework which I have created for running a stable PHP IRC Bot. 
With easily customizable features such as modules, it's simple for simple users, yet has the capabilities for developers to hook advanced plugins into.

Beta Notice
-------------
Right now, the bot is in beta and will be subject to rapid changes in the way parts work, it is not recommended for the bot to be used in a production environment until v1.0 is released.

Prerequisites
-------------
* PHP 5.3+ CLI (will NOT run on 5.2)
* Screen or another shell backgrounding utility if you want to background the bot

Installation
-------------
1. Copy all the files to a directory of your choice
2. Rename "config/config.example.php" to "config/config.php" and edit it

Startup
-------------
1. Navigate to the directory where the script is stored in a shell
2. Type "php start.php" into the shell and hit Enter

Debugging
-------------
1. Navigate to the directory where the script is stored in a shell
2. Type "php bot.php" into the shell and hit Enter
3. Watch the verbose output for signs of trouble

Installing Modules From The modules-extras Folder
-------------
The /modules-extras/ folder contains contributed and other non essential modules for your use.
To install a module pack, follow the instructions below:
1. Copy the module folder you want from /modules-extras/ into /modules/
2. Check the README in the modules folder for info on what line to add to bot.php
3. Restart the bot

Installing Other Modules
-------------
If a developer has sent you a module, or you've found it on the internet somewhere, follow the below instructions to install it
1, Copy the module folder into /modules/
2, Write down the name of the folder you copied
3, Open the module folder you copied and go into the "configs" folder, write down the name of the file in there, without the .php (case sensitive)
4, Open bot.php and add the following line, replacing the necessary parts
	ModuleManager::loadModuleConfig('modules\NAME-FROM-STEP-2\configs\NAME-FROM-STEP-3');
5, Restart the bot

Legal
-------------
By using Awesome IRC Bot, you agree to the license in LICENSE.md