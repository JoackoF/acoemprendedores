# Usa una imagen de PHP con Apache
FROM php:8.2-apache

# Instala las extensiones necesarias para MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Habilita los logs de errores en pantalla
RUN echo 'error_reporting = E_ALL' >> /usr/local/etc/php/conf.d/error-logging.ini \
    && echo 'display_errors = On' >> /usr/local/etc/php/conf.d/error-logging.ini

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia el código fuente a la imagen
COPY ./src /var/www/html

# Da permisos al directorio (para logs, caché, etc.)
RUN chown -R www-data:www-data /var/www/html

# Expone el puerto 80 para acceder a la aplicación
EXPOSE 80

# Inicia Apache
CMD ["apache2-foreground"]