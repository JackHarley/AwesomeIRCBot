# Awesome IRC Bot Framework
#### Powerful, User Friendly PHP IRC Bot Framework
#### Created by Jack Harley
#### v0.4.0

Introduction
-------------
Awesome IRC Bot is a powerful framework which I have created for running a stable PHP IRC Bot. 
With easily customizable features such as modules, it's simple to use, yet has the capabilities for developers to hook advanced plugins into.

Prerequisites
-------------
* PHP 5.3+ CLI (apt-get install php5-cli)
* SQLite PDO Extension (apt-get install php5-sqlite)

Installation
-------------
1. Copy all the files to a directory of your choice

Startup
-------------
1. Navigate to the directory where the script is stored in a shell
2. Type "php bot.php" into the shell and hit Enter
3. If this is your first run, you will be prompted for configuration values

Installing Modules From The modules-extras Folder
-------------
The /modules-extras/ folder contains contributed and other non essential modules for your use.
To install a module pack, follow the instructions below:

1. Copy the module folder you want from /modules-extras/ into /modules/
2. Restart the bot

Uninstalling Modules
-------------
To uninstall a module set, follow the instructions below

1. Delete the module folder from /modules/
2. Restart the bot

Using the Bot
-------------
Type .help on a channel the bot is on (replace . with your command prefix), to get information on the commands and functions available for use

Legal
-------------
By using Awesome IRC Bot, you agree to the license in LICENSE.md