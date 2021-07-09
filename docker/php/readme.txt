in terminal : 

docker exec -it {container-name} bash

apt-get update
apt-get install zip unzip
cd ~
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
cd /var/www/html
php ~/composer.phar require thenetworg/oauth2-azure

php ~/composer.phar require slim/slim
php ~/composer.phar require slim/psr7
php ~/composer.phar require slim/http  
php ~/composer.phar require php-di/php-di --with-all-dependencies

php ~/composer.phar require tuupola/slim-jwt-auth


enable mod rewrite
    a2enmod rewrite
    a2enmod headers
    service apache2 restart