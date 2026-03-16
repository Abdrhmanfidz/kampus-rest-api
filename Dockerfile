FROM php:8.2-apache

# install mysqli
RUN docker-php-ext-install mysqli

# copy project ke apache root
COPY . /var/www/html/

# aktifkan apache rewrite
RUN a2enmod rewrite

EXPOSE 80
