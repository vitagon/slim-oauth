FROM php:8.0-fpm-alpine

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

RUN apk add --no-cache \
    autoconf g++ make \
    icu-dev gcc autoconf curl bzip2-dev libzip-dev freetype-dev libjpeg-turbo-dev libpng-dev \
    git vim

RUN pecl install xdebug-3.0.1 \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable xdebug

RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install -j$(nproc) bcmath bz2 gd intl opcache pdo_mysql zip exif pcntl
RUN curl -sS http://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --ansi --version --no-interaction

COPY ./common/php/conf.d /usr/local/etc/php/conf.d
COPY ./dev/php-fpm/conf.d /usr/local/etc/php/conf.d

WORKDIR /app
