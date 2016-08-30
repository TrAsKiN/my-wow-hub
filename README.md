# My WoW Hub

A simple WoW Hub in PHP using **Battle.net API** and **Silex**.

View a demonstration on my website : http://hub.my-wow.com/

## Installation

### Step 1

Install [Composer](https://getcomposer.org/) to automate the installation and update components.

Clone the repository and install components with the command `composer install` in the application path.

### Step 2

Copy and rename the file `conf.php.dist` to `conf.php` and change the values with yours (see https://dev.battle.net/apps/mykeys).

## Upgrade from an old installation

Copy your `conf.php` file. If your file is the first version, then it contains lines that were deleted. Look at the new file and keep only the corresponding lines.

Update your repository with `git pull` command. Move your `conf.php` file in the new folder `app/config/`.
