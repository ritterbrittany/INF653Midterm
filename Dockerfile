#Use an official PHP image with Apache
FROM php:8.2-apache

#Install requried system packages and dependencies 
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

#Set the working directory in the container
WORKDIR /var/www/html

#Copy the current directory content into the container at /var/www/html
COPY . /var/www/html/

#Install any dependencies your PHP application may need 
#For example, 
#RUN apt-get update && apt-get install -y \
#     git \
#     && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#Adding Postgres support:
RUN docker-php-ext-install pdo_pgsql

#Copy custom Apache configuration 
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Enable Apache modulkes
RUN a2enmod rewrite

#Set Apache to bind to IP address 0.0.0.0
# RUN echo "Listen 0.0.0.0:80" >> /etc/apache2/apache2.conf

#Expose port 80 to allow incoming connections to the container
EXPOSE 80

#By default, Apache is started automatically. You can change or customize startup command if necessary.
# CMD ["apache2-foreground"]