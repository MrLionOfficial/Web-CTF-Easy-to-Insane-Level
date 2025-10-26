FROM php:8.2-apache

# Install required extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy application files
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Create flag file
RUN echo "CTF{xss_master_insane}" > /var/www/html/flag.txt
RUN chown www-data:www-data /var/www/html/flag.txt
RUN chmod 644 /var/www/html/flag.txt
