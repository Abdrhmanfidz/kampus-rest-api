FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    libssl-dev \
    && docker-php-ext-install mysqli \
    && docker-php-ext-enable mysqli

COPY . /var/www/html/

WORKDIR /var/www/html

CMD php -S 0.0.0.0:${PORT:-8080}
