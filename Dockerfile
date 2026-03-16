FROM php:8.2-apache

RUN apt-get update && \
    a2dismod mpm_event || true && \
    a2dismod mpm_worker || true && \
    a2dismod mpm_prefork || true && \
    a2enmod mpm_prefork

RUN docker-php-ext-install mysqli

COPY . /var/www/html/

RUN a2enmod rewrite

EXPOSE 80
