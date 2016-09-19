# My WoW Hub
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/267657b8-5f65-45e0-a83d-9666b609470e/mini.png)](https://insight.sensiolabs.com/projects/267657b8-5f65-45e0-a83d-9666b609470e)

A simple WoW Hub in PHP using **Battle.net API** and **Silex**.

## Installation
### Pre-require
- [Composer](https://getcomposer.org/) to automate the installation and update components.

### Step 1
To install the application, you have 3 choices:
 - Clone the github repository;
 - Download the [latest release](https://github.com/TrAsKiN/my-wow-hub/releases/latest);
 - Create a new project with composer (`composer create-project traskin/my-wow-hub`).

Install application components by running `composer install` from the application folder.

### Step 2
Configure your application by duplicating the `app/config/conf.php.dist` in `app/config/conf.php` and change the values with your own.

The values of the Battle.net API are available on the website: https://dev.battle.net/apps/mykeys.

### Step 3
To access your application, configure your web server on the `web` folder.

### It's done!

## Contribute
You need help to contribute on the development of the application? Contact me on [Twitter](https://twitter.com/notTrAsKiN).

## License
This project is under **MIT license**.
