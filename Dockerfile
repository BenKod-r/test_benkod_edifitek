FROM php:8.1-apache
RUN a2enmod rewrite
RUN apt-get update
RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get install -y zlib1g-dev libicu-dev g++
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl
COPY . /var/www/html
ADD ./000-default.conf /etc/apache2/sites-available
