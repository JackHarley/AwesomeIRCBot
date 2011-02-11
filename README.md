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

Using Modules
-------------
1. Copy the module into /modules/, and its config into /modules/configs/
2. Open bot.php and find the line ModuleManager::loadModuleConfig('modules\configs\SystemCommands');
3. Below it, put a new line saying ModuleManager::loadModuleConfig('modules\configs\REPLACE-WITH-THE-NAMEOF-THE-MODULE-CONFIG');
4. Replace REPLACE-WITH-THE-NAMEOF-THE-MODULE-CONFIG with the name of the file you copied into /modules/configs/ (without the .php)
5. Restart the bot

Legal
-------------
By using Awesome IRC Bot, you agree to the license in LICENSE.md