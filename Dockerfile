# Use the official PHP image with Apache
FROM php:7.4-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . /var/www/html

# Fix permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Install application dependencies
RUN composer install --prefer-dist --no-dev --no-scripts --no-progress --no-interaction

# Set environment variables
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Override the default Apache configuration
RUN echo "\
    <VirtualHost *:80>\n\
        DocumentRoot /var/www/html/public\n\
        <Directory /var/www/html/public>\n\
            Options Indexes FollowSymLinks\n\
            AllowOverride All\n\
            Require all granted\n\
        </Directory>\n\
    </VirtualHost>\n\
" > /etc/apache2/sites-available/000-default.conf

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
