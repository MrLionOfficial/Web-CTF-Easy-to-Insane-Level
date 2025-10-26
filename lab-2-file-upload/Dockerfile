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

# Create uploads directory
RUN mkdir -p /var/www/html/uploads
RUN chown -R www-data:www-data /var/www/html/uploads
RUN chmod 755 /var/www/html/uploads

# Create flag file
RUN echo "CTF{command_injection_master}" > /var/www/html/flag.txt
RUN chown www-data:www-data /var/www/html/flag.txt

# Expose port 80 (default for Apache)
EXPOSE 80
