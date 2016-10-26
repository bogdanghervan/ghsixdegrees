#!/bin/bash

echo "Provisioning virtual machine..."

echo "Installing application stack..."
apt-get update
apt-get install -y apache2 libapache2-mod-php \
    php-mod php-mbstring php-mcrypt php-zip screen git

echo "Configuring virtual host..."
cat <<EOF > /etc/apache2/sites-available/ghsixdegrees.conf
<VirtualHost *:80>
    ServerName ghsixdegrees.local
    DocumentRoot /var/www/ghsixdegrees/public

    <Directory "/var/www/ghsixdegrees/public">
        AllowOverride all
    </Directory>
    
    php_value display_errors 1
</VirtualHost>
EOF
a2ensite ghsixdegrees
a2enmod rewrite
service apache2 reload

echo "Installing Composer..."
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
composer install

echo "Installing Xdebug..."
apt-get install -y php5-dev
pecl install xdebug
cat <<EOF > /etc/php5/mods-available/xdebug.ini
zend_extension=xdebug.so
xdebug.remote_enable=1
xdebug.remote_host=192.168.50.1
xdebug.var_display_max_data=4096
xdebug.var_display_max_depth=4
EOF
php5enmod xdebug
service apache2 reload

echo "Installing test crontab..."
crontab -u www-data /var/www/cronkeep/provision/crontabfile

echo "Finished provisioning."