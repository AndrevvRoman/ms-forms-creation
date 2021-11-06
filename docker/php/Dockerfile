FROM php:7.4-fpm

RUN apt update
RUN apt install -y \
    zlib1g-dev libicu-dev libzip-dev libonig-dev  g++ \
    git \
    mariadb-client \
    vim \
    curl \
    zip \
    librabbitmq-dev \
    libssh-dev \
    && docker-php-ext-install \
    bcmath \
    sockets \
    && pecl install amqp \
    && docker-php-ext-enable amqp \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && docker-php-ext-install sysvsem \
    && docker-php-ext-install -j$(nproc) mbstring \
    && docker-php-ext-install -j$(nproc) pdo \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-install -j$(nproc) pdo_mysql \
    && docker-php-ext-install -j$(nproc) mysqli \
    && docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-install opcache

WORKDIR /var/www/php_forms_creation

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony