# blogsapi
A simple boilerplate for a REST API server based on the Slim PHP framework with mysqli as a database connection

## Getting Started
These instructions will get you a copy of the project up and running on your local machine to be used as a boiler plate template for a simple blogs API. The API consists of users, comments and posts.

### Prerequisites

```
PHP ^7.0
```

```
Composer
```

```
MySQL
```

## Built With
* [Slim PHP](https://www.slimframework.com/#community) - PHP Framework
* [Composer](https://getcomposer.org/) = Dependency Manager

## Installation Instructions

1. Clone the repo to the webserver folder (e.g. for Xampp, in htdocs)
2. Open up terminal, go to the root folder and run 

```
composer install
```
If composer is not installed globally, go to the root folder and run

```
php composer.phar install
```

3. Go to schema folder and import the api_db.sql file to you database server.
4. Edit the httpd-vhosts.conf. 
```
	<VirtualHost *:80>
	    DocumentRoot "path/to/project/public_folder/"
	    ServerName domainname
	</VirtualHost>
```
5. edit the host files. It should have "127.0.0.1 domainname" at the end
6. Restart apache2 service
7. Make sure that AllowOverride All is enabled.
8. Edit the DB settings found in /src/settings.php

## Authors

* **Ryan Bamba** - [deviance22](https://github.com/deviance22)