FROM php:8.3-apache

# Install System Dependencies & Required Extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) mysqli pdo_mysql gd zip opcache

# Enable Apache Rewrite Module
RUN a2enmod rewrite

# Copy Application Code
COPY . /var/www/html/

# Set Proper Permissions for Uploads and Logs
RUN chown -R www-data:www-data /var/www/html/uploads /var/www/html/application/logs \
    && chmod -R 775 /var/www/html/uploads /var/www/html/application/logs

# Expose HTTP Port
EXPOSE 80

CMD ["apache2-foreground"]
