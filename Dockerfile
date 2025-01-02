# Usa una imagen base con PHP
FROM php:8.3-fpm

# Instala las dependencias necesarias
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev libzip-dev zip

# Instala las extensiones de PHP necesarias
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd pdo pdo_mysql zip

# Establece el directorio de trabajo
WORKDIR /var/www

# Copia el código fuente de la aplicación al contenedor
COPY . .

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instala las dependencias de Composer
RUN composer install --no-dev --optimize-autoloader

# Expone el puerto en el que la aplicación escuchará
EXPOSE 80

# Inicia PHP-FPM
CMD ["php-fpm"]
