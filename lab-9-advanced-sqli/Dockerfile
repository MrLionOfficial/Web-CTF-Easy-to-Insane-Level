FROM php:8.2-apache

# Install MySQLi and PDO extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy application files
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Create flag file
RUN echo "CTF{advanced_sqli_master_insane}" > /var/www/html/flag.txt
RUN chown www-data:www-data /var/www/html/flag.txt
RUN chmod 644 /var/www/html/flag.txt

# Script to initialize the database
RUN echo '#!/bin/bash' > /usr/local/bin/init-advanced-db.sh && \
    echo 'sleep 10' >> /usr/local/bin/init-advanced-db.sh && \
    echo 'php /var/www/html/init-advanced-db.php' >> /usr/local/bin/init-advanced-db.sh && \
    chmod +x /usr/local/bin/init-advanced-db.sh

# Run the init script in background
CMD ["sh", "-c", "/usr/local/bin/init-advanced-db.sh & apache2-foreground"]
