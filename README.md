Core Admin Application
=======================

Introduction
------------

This is a extendable API that uses native zend validation to inspect incoming data by annotating data container objects. 

Includes native zend ACL and Authentication. PHPUnit tested

Installation
------------


Using Git
--------------------


    git clone git@bitbucket.org:lesovik/zend-2-api.git
    
If you have authentication problems, check your access to bitbucket via to ensure your ssh key is added correctly:

    ssh -T git@bitbucket.org   

Windows users should authenitcate over https: https://[username]@bitbucket.org/lesovik/zend-2-api.git

Dependency Management
--------------------
### Composer
Dependencies are managed by composer. To install composer globally (you will only need to do this once for all projects) paste 
the following into the terminal:

    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer

This assumes /usr/local/bin is already added to your PATH. Then, just run composer in order to run Composer instead of php composer.phar. Windows should use the exe to install: https://getcomposer.org/Composer-Setup.exe

### Install dependencies:

    composer install



Web Server Setup
----------------

### PHP CLI Server

The simplest way to get started if you are using PHP 5.4 or above is to start the internal PHP cli-server in the root directory:

    php -S 0.0.0.0:8080 -t public/ public/index.php

This will start the cli-server on port 8080, and bind it to all network
interfaces.

**Note: ** The built-in CLI server is *for development only*.

### Apache Setup

To setup Apache 2.4, setup a virtual host to point to the public/ directory of the
project and you should be ready to go! It should look something like below:

    <VirtualHost *:80>
        ServerName zend2api.local
        DocumentRoot /path/to/zend-2-api/public
        SetEnv APPLICATION_ENV "testing"
        <Directory /path/to/zend-2-api/public>
            DirectoryIndex index.php
            AllowOverride All
            Order allow,deny
            Allow from all
        </Directory>
    </VirtualHost>

### Create host file 
Edit your /etc/hosts file to run on a the ServerName specified above:

    sudo vi /etc/hosts
    127.0.0.1 zend2api.local


Testing
----------------
Use phpunit for global and per-module testing

    cd /test /phpunit --colors