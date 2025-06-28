#!/bin/bash

# ################################################################################################ #
#                                        Application Bootstrap                                     #
# ################################################################################################ #

set -euo pipefail

# Get current working directory
CURRENT_DIR=$(pwd)

# Get directory of this script
SCRIPT_DIR=$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)

APP_ENV=${1:-local}
APP_ENV_EXAMPLE=${APP_ENV_EXAMPLE:-"${CURRENT_DIR}/.env.example"}

echo "▣ Bootstrapping Application..."

if [[ ! -f "${CURRENT_DIR}/.env" ]]; then
    echo "▣ Fresh Installation Detected!"
    echo "▣"

    # Copy example env file to .env (source can be changed via APP_ENV_EXAMPLE)
    cp ${APP_ENV_EXAMPLE} "${CURRENT_DIR}/.env"

    # Install composer dependencies if vendor directory does not exist
    if [[ ! -d "${CURRENT_DIR}/vendor" ]]; then
        echo "🚀 Installing Composer Dependencies..."
        
        # Install composer dependencies without dev dependencies
        composer check-platform-reqs && \
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
        
        # Install composer dependencies with dev dependencies for local environment
        if [[ "${APP_ENV}" == "local" ]]; then
            echo "🚀 Installing Composer Dev Dependencies..."
            composer install \
                --no-interaction \
                --no-progress \
                --ignore-platform-reqs \
                --no-plugins \
                --no-scripts \
                --prefer-dist
        fi
    fi

    composer dump-autoload --optimize

    # Generate application key only if APP_KEY not set
    if [[ -z "${APP_KEY:-}" ]]; then
        echo "🚀 Generating Application Key..."
        php artisan key:generate
    fi

    echo "🚀 Running migrations..."
    php artisan migrate --env=${APP_ENV} --force

    php artisan config:cache
    php artisan route:cache
    php artisan event:cache
    php artisan view:cache
else
    echo "▣ Existing Installation Detected!"
    echo "▣"

    composer dump-autoload --optimize

    # php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    php artisan event:clear
    php artisan view:clear
    php artisan config:cache
    php artisan route:cache
    php artisan event:cache
    php artisan view:cache

    echo "🚀 Running migrations..."
    php artisan migrate --env=${APP_ENV} --force
fi

echo "▣ Application Bootstrapped!"
