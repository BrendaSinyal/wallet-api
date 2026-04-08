FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libsqlite3-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_sqlite pdo_pgsql pgsql mbstring zip \
    && a2enmod rewrite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . /var/www/html

RUN composer install --no-dev --optimize-autoloader

RUN mkdir -p /var/www/html/database \
    && touch /var/www/html/database/database.sqlite \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

RUN php artisan config:clear \
    && php artisan route:clear \
    && php artisan cache:clear \
    && php artisan view:clear

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

RUN sed -i 's/80/10000/g' /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 10000

CMD ["apache2-foreground"]