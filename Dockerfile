FROM wordpress:php8.2-apache
# Enable Apache modules you need
RUN a2enmod rewrite
RUN chown -R www-data:www-data /var/www/html