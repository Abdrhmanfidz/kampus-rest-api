FROM php:8.2-cli

# Install mysqli
RUN docker-php-ext-install mysqli

# Copy project
COPY . /var/www/html/

WORKDIR /var/www/html

EXPOSE 80

CMD ["php", "-S", "0.0.0.0:80"]
