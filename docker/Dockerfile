FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    vim \
    openssl \
    zip \
    libzip-dev \
    curl \
    wget \
    gnupg \
    apt-transport-https \
    lsb-release \
    libicu-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libicu-dev \
    libmagickwand-dev \
    libbz2-dev \
    libfontconfig && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install calendar zip intl pdo_mysql opcache pdo

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

COPY ./config/php.ini /usr/local/etc/php/

WORKDIR /code

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

RUN sed -i -e "s/pm.max_children\s*=\s*[0-9]*/pm.max_children = 30/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i -e "s/pm.max_spare_servers\s*=\s*[0-9]*/pm.max_spare_servers = 10/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i -e "s/;pm.max_requests\s*=\s*[0-9]*/pm.max_requests = 500/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i -e "s/access.log/;access.log/g" /usr/local/etc/php-fpm.d/docker.conf

EXPOSE 9000
CMD ["php-fpm"]
