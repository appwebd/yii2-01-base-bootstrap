<p align="center">
    <a href="http://www.yiiframework.com/" target="_blank">
        <img src="https://www.yiiframework.com/files/logo/yii.png" width="400" alt="Yii Framework" />
    </a>
    <h1 align="center">Yii Framework Application</h1>
    <br>
</p>

This package is [Yii Framework] application best for rapidly creating projects.

This template, contains the basic features including user login/logout and a contact page.
It includes all commonly used configurations that would allow you to focus on adding new
features to your application.

[Yii Framework]: http://www.yiiframework.com/

DIRECTORY STRUCTURE
-------------------

```
assets/             contains assets definition
commands/           contains console commands (controllers)
components/         contains web components classes mainly (user interface)
config/             contains application configurations
controllers/        contains Web controller classes
docs/               contains MySQL script initial configuration of the database.
helpers/            contains helper functions for Yii Framework 2.0.  
mail/               contains view files for e-mails
messages/           contains messages traduction in language spanish
models/             contains model classes
  forms/            contains model classes of forms
  queries/          contains common queries in the data model 
  search/           contains model classes of search mainly used in index view
runtime/            contains files generated during runtime
public/             contains the entry script for a web server  
tests/              contains various tests for the basic application
views/              contains view files for the Web application
vendor/             contains dependent 3rd-party packages
composer.json
sonar-project.properties Sonarqube configuration for sonar-scanner
```

REQUIREMENTS
------------
 

The minimum requirement by this project template that your Web server supports PHP 7.0.


INSTALLATION
------------

You can install this project template using the following command:

~~~

git clone https://github.com/appwebd/yii2-base.git

~~~

CONFIGURATION
-------------

### MySQL Database configuration

Edit the file `config/db.php` with real data, for example:

```php
return [
    '__class' => \yii\db\Connection::class,
    'dsn' => 'mysql:host=localhost;dbname=db_base',
    'username' => 'user',
    'password' => 'the-user-password',
    'charset' => 'utf8',
];
```

In the directory docs/ (root directory), you can found a MySQL script db_base.sql; With it you can re-create the database initial db_base

~~~

mysql -u root -p < docs/db_base.sql    

~~~

The initial administrator account like user and password is admin admin

**NOTES:**
- Yii won't create the database for you, this has to be done manually before you can access it.
- Check and edit the other files in the `config/` directory to customize your application as required.
(changing for example language).
- Refer to the README in the `tests` directory for information specific to basic application tests.


TESTING
-------

Tests are located in `tests` directory.
