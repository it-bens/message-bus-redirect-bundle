## build context is at docker/
FROM php:8.0-cli
WORKDIR /var/www/html

# install dependencies
RUN set -ex; \
    apt-get update && \
    apt-get -y --no-install-recommends install \
    $PHPIZE_DEPS \
	bash \
    git \
    zip

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions \
    xdebug-^3.1 \
    zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

VOLUME /var/www/html
