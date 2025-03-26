# Use an official PHP image with Apache
FROM php:8.2-apache

# Install required system packages and dependencies 
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the current directory content into the container at /var/www/html
COPY . /var/www/html/

# Install PostgreSQL support (pdo_pgsql)
RUN docker-php-ext-install pdo_pgsql

# Enable Apache modules (rewrite and headers for CORS)
RUN a2enmod rewrite headers

# Copy custom Apache configuration (this assumes you have an apache.conf file)
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Set Apache to bind to IP address 0.0.0.0
RUN echo "Listen 0.0.0.0:80" >> /etc/apache2/apache2.conf

# Expose port 80 to allow incoming connections to the container
EXPOSE 80

# By default, Apache is started automatically. You can change or customize the startup command if necessary.
CMD ["apache2-foreground"]
