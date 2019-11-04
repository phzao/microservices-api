FROM php:7.2.2-fpm

RUN apt-get update && apt-get install -y apt-utils mysql-client git vim openssl zip unzip git libzip-dev libmcrypt-dev libmagickwand-dev \
    && docker-php-ext-install pdo_mysql\
    && docker-php-ext-configure zip --with-libzip=/usr/include\
    && docker-php-ext-install zip\
    && pecl install imagick mcrypt-1.0.1 \
    && docker-php-ext-enable imagick mcrypt

RUN groupadd dev -g 999
RUN useradd dev -g dev -d /home/dev -m

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
