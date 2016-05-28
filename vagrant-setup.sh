#!/bin/sh

#Ignore the grub package so it does not stop the install process
sudo apt-mark hold grub*

#add php7 PPA
sudo add-apt-repository -y ppa:ondrej/php-7.0

#remove apache2 and php in-case they are already on the base box
sudo apt-get remove apache2 php*

#Update/Upgrade apt
sudo apt-get -y update && sudo apt-get -y upgrade

#install mysql
export DEBIAN_FRONTEND=noninteractive
sudo -E apt-get -y install mysql-server

#install applications
sudo -E apt-get -y install php7.0 php7.0-* nginx

#change permissions of this file so PHP error reporting can be enabled
sudo chmod 0666 /etc/php/7.0/fpm/php.ini

#the string of data that will be appended to the php.ini file
str="\n\nerror_reporting = E_ALL\ndisplay_errors = On\n"

#write the string to the php.ini
sudo echo -e "$str" >> /etc/php/7.0/fpm/php.ini

sudo rm -rf /etc/nginx/sites-available/* /etc/nginx/sites-enabled/*
sudo ln -s /vagrant/vagrant-setup-files/nginx/framework.local.conf /etc/nginx/sites-enabled/framework.local.conf

#restart apache to enable mod_rewrite & SSL
sudo service nginx restart

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
sudo ln -s /vagrant /var/
sudo mv /var/vagrant /var/www

sudo mkdir -p /var/log/framework
sudo chmod 0777 /var/log/framework
