FROM php:8.2-apache

# Fix MPM conflict - force disable semua dulu baru enable prefork
RUN apt-get update && \
    a2dismod mpm_event || true && \
    a2dismod mpm_worker || true && \
    a2dismod mpm_prefork || true && \
    a2enmod mpm_prefork

# Install mysqli
RUN docker-php-ext-install mysqli

# Copy project ke apache root
COPY . /var/www/html/

# Aktifkan apache rewrite
RUN a2enmod rewrite

EXPOSE 80
