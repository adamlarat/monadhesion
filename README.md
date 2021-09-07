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

## Sources 
* https://www.developpez.net/forums/d1930668/php/php-base-donnees/script-php-formulaire-recherche-simple-bdd/
* https://openclassrooms.com/fr/courses/918836-concevez-votre-site-web-avec-php-et-mysql/913099-transmettez-des-donnees-avec-les-formulaires
* https://www.delftstack.com/fr/howto/mysql/mysql-import-csv/
* https://openclassrooms.com/forum/sujet/remplissage-table-mysql-avec-un-fichier-csv
* https://analyse-innovation-solution.fr/publication/fr/php/comment-envoyer-un-mail-en-php

