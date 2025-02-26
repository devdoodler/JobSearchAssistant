# Use the official PHP image
FROM php:8.3-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    nginx \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip \
    && apt-get clean

# Install Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN rm /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
# Configure Xdebug
COPY ./xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/symfony

VOLUME /var/www/symfony/var/
VOLUME /var/www/symfony/vendor/

# Copy Symfony app files into the container
COPY ./symfony_app /var/www/symfony

# Install Symfony dependencies
RUN composer install

# Copy the Nginx configuration file to the container
COPY nginx/default.conf /etc/nginx/conf.d/default.conf

# Expose port for Nginx
EXPOSE 80

# Start both PHP-FPM and Nginx
#CMD service nginx start && php-fpm
CMD php-fpm & nginx -g 'daemon off;'
