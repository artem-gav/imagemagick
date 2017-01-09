FROM php:5.6-apache

COPY php.ini /usr/local/etc/php/
RUN apt-get update \
  && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng12-dev libmcrypt-dev libmagickwand-dev --no-install-recommends \
  && docker-php-ext-install pdo_mysql mysqli mbstring gd iconv mcrypt \
  && pecl install imagick \
  && docker-php-ext-enable imagick \
  && rm -r /var/lib/apt/lists/*