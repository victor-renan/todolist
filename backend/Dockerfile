FROM dunglas/frankenphp

RUN install-php-extensions \
 pdo_mysql \
 mysqli \
 gd \
 pcntl

COPY . /app

ENTRYPOINT ["php", "artisan", "octane:frankenphp"]