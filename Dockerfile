FROM php:8.2-fpm

# Instalar dependencias esenciales del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libzip-dev \
    libonig-dev \
    libxml2-dev

# Limpiar caché
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones esenciales de PHP
RUN docker-php-ext-install pdo_mysql mbstring zip xml

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar el código de la aplicación
COPY . .

# Instalar dependencias de Composer (sin dependencias de desarrollo)
#RUN composer install --optimize-autoloader --no-dev --no-interaction --prefer-dist

# Permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache