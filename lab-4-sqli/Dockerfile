FROM php:8.2-apache

# Install necessary PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy the application files to the Apache document root
COPY . /var/www/html/

# Set appropriate permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Create a simple script to initialize database
RUN echo '#!/bin/bash' > /usr/local/bin/init-db.sh && \
    echo 'php /var/www/html/init-db.php' >> /usr/local/bin/init-db.sh && \
    chmod +x /usr/local/bin/init-db.sh

# Expose port 80 (default for Apache)
EXPOSE 80
