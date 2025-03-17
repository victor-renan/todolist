FROM dunglas/frankenphp

RUN install-php-extensions \
 pdo_mysql \
 mysqli \
 gd \
 pcntl

COPY . /app

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN composer install

RUN php artisan migrate

ENTRYPOINT ["php", "artisan", "octane:frankenphp"]