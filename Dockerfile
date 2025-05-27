# Usa una imagen de PHP con Apache
FROM php:8.2-apache

# Instala dependencias del sistema para PostgreSQL y luego las extensiones de PHP
RUN apt-get update \
    && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

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