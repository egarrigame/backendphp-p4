# imagen base que ya teníamos, sirve como base para después añadir extensiones
FROM php:8.2-apache  

# módulo de apache para escribir las urls de Laravel más sencillas 
RUN a2enmod rewrite

# instalamos las extensiones y paquetes para laravel
# git y unzip para descargar y descomprimir librerías
# libzip para que php pueda trabajar con zips
# pdo_mysql para que php pueda trabajar con mysql (driver)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql zip

# copiamos el archivo ejecutable de composer desde la imagen oficial, así se puede usar sin descargar ni instalar
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# hacemos que apache tome los archivos desde la carpeta /public de laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# le decimos que cuando entremos a un contenedor trabajaremos en la carpeta del servidor web
WORKDIR /var/www/html