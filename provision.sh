#!/usr/bin/env bash

sudo apt-get update

# ========================================
# install apache

sudo apt-get install -y apache2

sudo a2enmod rewrite
sudo service apache2 restart


# ========================================
# install mysql

MYSQL_ROOT_PASSWORD="vagrant1"

# prevent the prompt screen from showing
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password $MYSQL_ROOT_PASSWORD"
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $MYSQL_ROOT_PASSWORD"

# install mysql server
sudo apt-get install -y mysql-server


# # ========================================
# # install redis
#
# sudo apt-get install -y redis-server


# ========================================
# install php

sudo apt-get install -y php libapache2-mod-php php-mcrypt php-mysql php-curl php-mbstring php-xml #php-redis


# ========================================
# setup virtual host

# create apache config
sudo bash -c 'cat <<EOT >>/etc/apache2/sites-available/karaoke2.conf
<VirtualHost *:80>
    ServerName karaoke2.vagrant
    DocumentRoot /var/www/karaoke2/public

    SetEnv APPLICATION_ENV "vagrantdev"

    <Directory /var/www/karaoke2/public/>
        Options FollowSymLinks
        AllowOverride All
    </Directory>

    # Logging
    ErrorLog /var/log/apache2/karaoke2-error.log
    LogLevel notice
    CustomLog /var/log/apache2/karaoke2-access.log combined
</VirtualHost>
EOT
'

sudo a2ensite karaoke2.conf
sudo service apache2 reload

# create databases
echo "create database karaoke2_dev" | mysql -u root -p$MYSQL_ROOT_PASSWORD
echo "create database karaoke2_test" | mysql -u root -p$MYSQL_ROOT_PASSWORD

# run migrations
cd /var/www/karaoke2
vendor/bin/phinx migrate --environment vagrantdev
vendor/bin/phinx migrate --environment vagranttest
