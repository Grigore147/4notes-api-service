ARG NGINX_VERSION="1.27.2-alpine"

# NGINX
FROM nginx:${NGINX_VERSION} AS nginx-base

ARG APP_PATH="app/"
ARG PHP_FPM_HOST="php-fpm-host"
ARG PHP_FPM_PORT="9000"

RUN <<EOF
    rm -rf /var/www/* /etc/nginx/conf.d
    adduser -u 1000 -D -S -G www-data www-data
    chown -R www-data:www-data /var/cache/nginx /etc/nginx/
    mkdir -p /app && chown -R www-data:www-data /app
EOF

WORKDIR /app

COPY --chown=www-data:www-data docker/nginx/nginx-* /usr/local/bin/
COPY --chown=www-data:www-data docker/nginx/nginx.conf /etc/nginx/
COPY --chown=www-data:www-data docker/nginx/config/* /etc/nginx/
COPY --chown=www-data:www-data docker/nginx/conf.d /etc/nginx/conf.d

RUN chmod +x /usr/local/bin/nginx-*

# Set the default log format to JSON
ENV NGINX_LOG_FORMAT="json"

# The PHP-FPM Host and Port are set as ENV variables
ENV PHP_FPM_HOST="${PHP_FPM_HOST}"
ENV PHP_FPM_PORT="${PHP_FPM_PORT}"

# Change to non root user
USER www-data

# For Documentation
EXPOSE 8080

# Add Healthcheck
HEALTHCHECK CMD ["nginx-healthcheck"]

# Add Entrypoint
ENTRYPOINT ["nginx-entrypoint"]


# NGINX PRODUCTION
FROM nginx-base AS nginx-production

# Copy public folder that's going to be served from Nginx
# NOTE: No full source code required here, since that is used/processed by PHP-FPM
COPY --chown=www-data:www-data public /app/public


# NGINX DEVELOPMENT
FROM nginx-base AS nginx-development

ENV NGINX_LOG_FORMAT="combined"

COPY --chown=www-data:www-data public /app/public
