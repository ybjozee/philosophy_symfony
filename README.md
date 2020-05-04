philosophy_symfony
==================

Project
=================
This is a demo Blog Application built with Symfony. The Layout was designed and developed by Colorlib. It demonstrates basic CRUD operations 
and Entity Relationships the Symfony way.


Deploying
=================

## Technical Requirements

Before creating your first Symfony application you must:
* Have a MySQL service running

* Have a News API token. You can get one [here](https://newsapi.org/register) 

* Install PHP 7.2.5 or higher and these PHP extensions (which are installed and enabled by default in most PHP 7 installations): 
[Ctype](https://www.php.net/book.ctype), [iconv](https://www.php.net/book.iconv), [JSON](https://www.php.net/book.json),[PCRE](https://www.php.net/book.pcre), [Session](https://www.php.net/book.session), [SimpleXML](https://www.php.net/book.simplexml), and [Tokenizer](https://www.php.net/book.tokenizer);
    
* Install [Composer](https://getcomposer.org/download/), which is used to install PHP packages.

* Optionally, you can also [install Symfony CLI](https://symfony.com/download). This creates a binary called symfony that provides all the tools you need to develop and 
run your Symfony application locally.

* The symfony binary also provides a tool to check if your computer meets all requirements. Open your console terminal and run this command:

        symfony check:requirements


Please visit [The Symfony Official Page](https://symfony.com/doc/current/setup.html) for more information on this project's technical 
requirements. 


## Installation
- Clone the project from github

        git clone https://github.com/ybjozee/philosophy_symfony

- cd into the project

        cd philosophy_symfony

- install the project dependencies

        composer install

- create your local folder

        cp .env .env.local

***Remember to update your News API Token in .env.local***

        NEWS_API_TOKEN=replaceWithYourNewsAPIToken

***Remember to update your DATABASE_URL in .env.local***

        DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7

- Create your database

        ./bin/console doctrine:database:create

- Run your migrations

        ./bin/console make:migration

- Load your fixtures

        ./bin/console doctrine:fixtures:load

- Type y when the warning shows up ***Do not load fixtures in production***

- Run your Symfony Application

        symfony serve
   
 - Open the application in your browser
 
        https://localhost:8000
