FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql

RUN echo 'error_reporting = E_ALL' >> /usr/local/etc/php/conf.d/error-logging.ini \
    && echo 'display_errors = On' >> /usr/local/etc/php/conf.d/error-logging.ini

WORKDIR /var/www/html

COPY ./src /var/www/html

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]