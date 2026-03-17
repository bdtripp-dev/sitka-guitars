FROM wordpress:php8.2-apache

# Ensure deploy group exists
RUN groupadd -g 1004 deploy || true

# Add www-data to deploy group so PHP can write to bind mounts
RUN usermod -aG deploy www-data
