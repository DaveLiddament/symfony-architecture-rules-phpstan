ARG PHP_VERSION=8.5
FROM php:${PHP_VERSION}-cli

# Increase memory limit
RUN echo 'memory_limit = -1' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install \
    zip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && rm -rf /var/lib/apt/lists/*

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV XDEBUG_MODE=debug

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Sort out git
RUN git config --global --add safe.directory /app

WORKDIR /app

CMD ["bash"]
