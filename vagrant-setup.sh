#!/bin/sh

#Ignore the grub package so it does not stop the install process
sudo apt-mark hold grub*

#remove apache2 and php in-case they are already on the base box
sudo apt-get -y remove apache2 php*

#add the PPA for PHP 7
str="\n\ndeb http://ppa.launchpad.net/ondrej/php/ubuntu trusty main\ndeb-src http://ppa.launchpad.net/ondrej/php/ubuntu trusty main\n\n"
sudo chmod 0777 /etc/apt/sources.list
sudo echo -e "$str" >> /etc/apt/sources.list
sudo chmod 0644 /etc/apt/sources.list

#Update/Upgrade apt
sudo apt-get -y update && sudo apt-get -y upgrade

#install mysql
export DEBIAN_FRONTEND=noninteractive
sudo -E apt-get -y install mysql-server

#install applications
sudo -E apt-get -y --force-yes install git php7.0 php7.0-bcmath php7.0-cli php7.0-curl php7.0-fpm php7.0-gd php7.0-json php7.0-mbstring php7.0-mcrypt php7.0-mysql php7.0-opcache php7.0-dev php7.0-zip nginx

#change permissions of this file so PHP error reporting can be enabled
sudo chmod 0666 /etc/php/7.0/fpm/php.ini

#the string of data that will be appended to the php.ini file
str="\n\nerror_reporting = E_ALL\ndisplay_errors = On\n"

#write the string to the php.ini
sudo echo -e "$str" >> /etc/php/7.0/fpm/php.ini

sudo rm -rf /etc/nginx/sites-available/* /etc/nginx/sites-enabled/*
sudo ln -s /vagrant/vagrant-setup-files/nginx/bedshop.local.conf /etc/nginx/sites-enabled/bedshop.local.conf

#restart apache to enable mod_rewrite & SSL
sudo service nginx restart

#clean the un-required packages
sudo apt-get -y autoremove && sudo apt-get -y autoclean

#change the file permission back to their original state
sudo chmod 0644 /etc/php/7.0/fpm/php.ini

#import the sql file to the database
mysql -uroot < /vagrant/vagrant-setup-files/database/import.sql

#create the anxiety user and grant all permissions
mysql -uroot -e "CREATE USER 'framework'@'localhost' IDENTIFIED BY 'framework';"
mysql -uroot -e "GRANT ALL PRIVILEGES ON * . * TO 'framework'@'localhost';"

#remove the current www folder
sudo rm -rf /var/www

#link the vagrant folder and change the links name to www
sudo ln -s /vagrant/public /var/
sudo mv /var/public /var/www

sudo mkdir -p /var/log/framework
sudo chmod 0777 /var/log/framework
sudo touch /var/log/framework/framework.log
sudo chmod 0666 /var/log/framework/framework.log

#setup composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '92102166af5abdb03f49ce52a40591073a7b859a86e8ff13338cf7db58a19f7844fbc0bb79b2773bf30791e935dbd938') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
sudo php composer-setup.php  --install-dir=/usr/local/bin --filename=composer
php -r "unlink('composer-setup.php');"
