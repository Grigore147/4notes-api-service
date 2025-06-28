ARG PHP_VERSION="8.2-fpm-alpine"
ARG PHP_BASE_IMAGE="php:${PHP_VERSION}"
ARG PHP_COMPOSER_VERSION="2.7.7"
ARG NODE_VERSION="22.11.0-alpine3.20"
ARG NODE_BASE_IMAGE="node:${NODE_VERSION}"

# NODE
FROM ${NODE_BASE_IMAGE} AS node


# COMPOSER
FROM composer:${PHP_COMPOSER_VERSION} AS composer


# PHP BASE IMAGE
FROM ${PHP_BASE_IMAGE} AS php-fpm-base

ARG TIMEZONE=UTC
ARG APP_PATH=/app

RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone

RUN apk --update add --no-cache \
    autoconf \
    bash \
    build-base \
    curl \
    freetype-dev \
    gettext-dev \
    grep \
    imagemagick \
    imagemagick-dev \
    jpeg-dev \
    jq \
    libedit-dev \
    libgcrypt-dev \
    libjpeg-turbo-dev \
    libmcrypt-dev \
    libpng-dev \
    librdkafka \
    librdkafka-dev \
    libxml2-dev \
    libxslt-dev \
    libzip-dev \
    linux-headers \
    oniguruma-dev \
    openldap-dev \
    openssl \
    openssl-dev \
    pcre-dev \
    postgresql-dev \
    rabbitmq-c \
    rabbitmq-c-dev \
    sed \
    shadow \
    tini \
    wget \
    yq \
    zlib-dev \
    && rm -rf /var/cache/apk/* /tmp/*

RUN pecl channel-update pecl.php.net && \
    pecl install mcrypt redis-5.3.7 imagick mongodb rdkafka amqp && \
    rm -rf /tmp/pear


RUN docker-php-ext-install \
        calendar \
        exif \
        gd \
        ldap \
        mysqli \
        mbstring \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        pdo_sqlite \
        opcache \
        xml \
        pcntl \
        bcmath \
        zip \
        intl \
        gettext \
        soap \
        sockets \
        xsl && \
    docker-php-ext-enable redis && \
    docker-php-ext-enable imagick && \
    docker-php-ext-enable mongodb && \
    docker-php-ext-enable rdkafka && \
    docker-php-ext-enable amqp

RUN chsh -s /bin/bash

ARG PHP_INI_DIR=/usr/local/etc/php
ARG PHP_FPM_DIR=/usr/local/etc/php-fpm.d

RUN cp ${PHP_INI_DIR}/php.ini-production ${PHP_INI_DIR}/php.ini

COPY docker/php/php.ini ${PHP_INI_DIR}/conf.d/
COPY docker/php/opcache.ini ${PHP_INI_DIR}/conf.d/

RUN usermod -u 1000 www-data && \
    rm -rf /var/www /usr/local/etc/php-fpm.d/* && \
    mkdir -p /.composer ${APP_PATH} && \
    chown -R www-data:www-data ${APP_PATH} /.composer

RUN cp "/etc/ssl/cert.pem" /opt/cert.pem

COPY docker/php/php-fpm.conf ${PHP_FPM_DIR}/

COPY docker/php/entrypoints /usr/local/bin/
RUN  chmod +x /usr/local/bin/entrypoint-*

COPY docker/scripts/app-bootstrap.sh /usr/local/bin/app-bootstrap
RUN chmod +x /usr/local/bin/app-bootstrap

# COPY --from=node /usr/lib /usr/lib
COPY --from=node /usr/local/share /usr/local/share
COPY --from=node /usr/local/lib /usr/local/lib
COPY --from=node /usr/local/include /usr/local/include
COPY --from=node /usr/local/bin /usr/local/bin

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR ${APP_PATH}

USER www-data

EXPOSE 9000

SHELL [ "/bin/bash", "-c" ]

ENTRYPOINT ["entrypoint-base"]

CMD ["php-fpm"]


# INSTALL DEPENDENCIES
FROM php-fpm-base AS dependencies

ARG APP_PATH=/app

WORKDIR ${APP_PATH}

# Copy Dependencies files
COPY composer.json composer.json
COPY composer.lock composer.lock

# Install Dependencies
RUN composer check-platform-reqs && \
    composer validate && \
    composer install \
        --no-interaction \
        --no-progress \
        --ignore-platform-reqs \
        --no-plugins \
        --no-scripts \
        --no-dev \
        --no-autoloader \
        --prefer-dist


# INSTALL NODE DEPENDENCIES
FROM node AS node-dependencies

ARG APP_PATH=/app
ARG APP_URL=http://localhost:8080

WORKDIR ${APP_PATH}

COPY --chown=www-data:www-data . .

# npm ci --omit=dev && npm cache clean --force --loglevel=error && npm run build
RUN npm install && npm run build


# PRODUCTION IMAGE
FROM php-fpm-base AS app-production

ARG PHP_INI_DIR=/usr/local/etc/php

ENV APP_ENV=production

# COPY docker/php/php.ini-production $PHP_INI_DIR/conf.d/

# Copy Dependencies
COPY --from=dependencies --chown=www-data:www-data /app/vendor /app/vendor
# Copy App Code
COPY --chown=www-data:www-data . .
# Copy built assets
COPY --from=node-dependencies --chown=www-data:www-data /app/public /app/public

ENTRYPOINT ["entrypoint-production"]

CMD ["php-fpm"]


# DEVELOPMENT IMAGE
FROM php-fpm-base AS app-development

ARG PHP_INI_DIR=/usr/local/etc/php
ARG XDEBUG_VERSION=3.3.1

ENV APP_ENV=dev
ENV APP_DEBUG=1

# Switch to root to install more stuff
USER root

RUN apk --update add --no-cache curl htop wget && \
    apk --update add --no-cache --virtual .build-deps autoconf g++ libtool make pcre-dev && \
    pecl install xdebug-${XDEBUG_VERSION} xhprof && \
    docker-php-ext-enable xdebug xhprof && \
    apk del .build-deps && \
    rm -rf /var/cache/apk/* /tmp/*

# Replace php.ini with development version
RUN cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# COPY docker/php/php.ini-development $PHP_INI_DIR/conf.d/
COPY docker/php/xdebug.ini ${PHP_INI_DIR}/conf.d/
COPY docker/php/opcache-development.ini ${PHP_INI_DIR}/conf.d/opcache.ini

# Switch back to non-root user
USER www-data

# Copy Dependencies
COPY --from=dependencies --chown=www-data:www-data /app/vendor /app/vendor
# Copy App Code
COPY --chown=www-data:www-data . .
# Copy built assets
COPY --from=node-dependencies --chown=www-data:www-data /app/public /app/public

ENTRYPOINT ["entrypoint-development"]

CMD ["php-fpm"]


# SUPERVISOR IMAGE
FROM php-fpm-base AS app-supervisor

ARG PHP_INI_DIR=/usr/local/etc/php

ENV APP_ENV=dev

# Switch to root to install more stuff
USER root

RUN apk --update add --no-cache supervisor && \
    rm -rf /var/cache/apk/* /tmp/*

RUN mkdir -p /etc/supervisor/conf.d /var/log/supervisor

COPY ./docker/queue-supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Switch back to non-root user
USER www-data

# Copy Dependencies
COPY --from=dependencies --chown=www-data:www-data /app/vendor /app/vendor
# Copy App Code
COPY --chown=www-data:www-data . .
# Copy built assets
COPY --from=node-dependencies --chown=www-data:www-data /app/public /app/public

ENTRYPOINT ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
