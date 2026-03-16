FROM php:8.2-apache

# Fix MPM conflict
RUN a2dismod mpm_event mpm_worker 2>/dev/null; a2enmod mpm_prefork

# Install mysqli
RUN docker-php-ext-install mysqli

# Copy project ke apache root
COPY . /var/www/html/

# Aktifkan apache rewrite
RUN a2enmod rewrite

EXPOSE 80
