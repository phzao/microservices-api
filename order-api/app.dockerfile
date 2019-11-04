FROM php:7.2.2-fpm

RUN apt-get update && apt-get install -y apt-utils mysql-client git vim openssl zip unzip libzip-dev libmcrypt-dev libmagickwand-dev \
    && docker-php-ext-install pdo_mysql\
    && docker-php-ext-configure zip --with-libzip=/usr/include\
    && docker-php-ext-install zip\
    && pecl install imagick mcrypt-1.0.1 \
    && docker-php-ext-enable imagick mcrypt

RUN groupadd dev -g 999
RUN useradd dev -g dev -d /home/dev -m

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer require predis/predis
RUN composer require cviebrock/laravel-elasticsearch

#####################################################################################################
###### Tools for analyzing PHP code, please uncomment only for install locally and after comment again.
#####################################################################################################

#RUN composer global require "phpunit/phpunit"

#ENV PATH /root/.composer/vendor/bin:$PATH

#RUN ln -s /root/.composer/vendor/bin/phpunit /usr/bin/phpunit

#RUN curl -L https://phar.phpunit.de/phploc.phar > /usr/local/bin/phploc \
#   && chmod +x /usr/local/bin/phploc

#RUN curl -L https://phar.phpunit.de/phpcpd.phar > /usr/local/bin/phpcpd && \
#   chmod +x /usr/local/bin/phpcpd

#RUN pear install PHP_CodeSniffer-3.4.2

#RUN curl -L https://github.com/FriendsOfPHP/PHP-CS-Fixer/releases/download/v$PHP_CS_FIXER_VERSION/php-cs-fixer.phar > /usr/local/bin/php-cs-fixer \
#   && chmod +x /usr/local/bin/php-cs-fixer \
#   && rm -rf /var/cache/apk/* /var/tmp/* /tmp/*

#RUN composer require povils/phpmnd

#RUN composer global require dephpend/dephpend:dev-master

#ARG VERSION="^1.0"

#RUN composer require bmitch/churn-php

#ENV PHP_METRICS_VERSION=2.2.0

#RUN curl -L https://github.com/phpmetrics/PhpMetrics/releases/download/v$PHP_METRICS_VERSION/phpmetrics.phar > /usr/local/bin/phpmetrics \
#   && chmod +x /usr/local/bin/phpmetrics \
#   && rm -rf /var/cache/apk/* /var/tmp/* /tmp/*
