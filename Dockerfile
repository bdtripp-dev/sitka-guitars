FROM wordpress:6.9.4-php8.3-apache
RUN groupadd -g 1004 deploy || true
RUN usermod -aG deploy www-data
COPY wp/wp-config.php /var/www/html/wp-config.php
