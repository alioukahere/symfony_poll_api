FROM php:8.2-fpm AS php

RUN apt update \
  && apt install -y libzip-dev libxslt-dev zip \
  && rm -rf /var/lib/apt/lists/* \
  && pecl install apcu \
  && docker-php-ext-install pdo_mysql zip xsl intl \
  && docker-php-ext-configure zip \
  && docker-php-ext-enable apcu pdo_mysql zip xsl intl

WORKDIR /usr/src/app
COPY ./ /usr/src/app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
