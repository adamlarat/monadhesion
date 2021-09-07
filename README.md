## Installation instructions

### Remote submodule

This web page relies on the external PHP library  __[PHPMailer](https://github.com/PHPMailer/PHPMailer.git)__.
It is currently added as a submodule of this repo. Once you have cloned the repo run
```sh
git submodule init
git submodule update
```

### DB and SMTP connections setup

Hosts, login, passwords aso of DB and SMTP connections are stored in out-of-webroot files. Currently `/var/www/dbUsers.php` and `/var/www/smtpUsers.php`. 
These files should contain the following variables: 
``` 
dbUsers.php 
```
```
<?php
  $dbHost = '****';
  $dbName = '****';
  $dbUser = '****'; 
  $dbPass = '****';
?>
```
``` 
smtpUsers.php 
```
```
<?php
  $smtpHost   = '****';
  $smtpPort   = '****';
  $smtpSecure = '****';
  $smtpUser   = '****';
  $smtpPass   = '****'; 
  $smtpAlias  = '****';
?>
```
