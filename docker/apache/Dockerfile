FROM php:8.1-apache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /server

COPY ./docker/apache/server.sh /startScript/

RUN apt-get update && apt-get install -my \
    git \
    zip \
    curl \
    wget \
    && curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony/bin/symfony /usr/local/bin/symfony \
    && docker-php-ext-install pdo_mysql

ENTRYPOINT ["bash", "/startScript/server.sh"]
