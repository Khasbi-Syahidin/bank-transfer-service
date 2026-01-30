FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    git \
    unzip \
    build-base \
    autoconf \
    libtool \
    make \
    libzip-dev \
    postgresql-dev \
    sqlite-dev \
    oniguruma-dev \
    libxml2-dev \
    icu-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    && docker-php-ext-install \
    pdo_sqlite \
    zip \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    opcache \
    intl \
    pdo_mysql \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && rm -rf /var/cache/apk/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
RUN git config --global --add safe.directory /var/www/html

COPY . /var/www/html

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
