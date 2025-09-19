FROM php:8.2-cli

# Install curl
RUN apt-get update && apt-get install -y curl

# Copy your PHP script into the container
COPY index.php /var/www/html/index.php

# Set working directory
WORKDIR /var/www/html

# Start PHP built-in server
CMD ["php", "-S", "0.0.0.0:10000", "index.php"]
