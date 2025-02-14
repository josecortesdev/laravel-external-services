#!/bin/sh

# Copy .env file if it doesn't exist
if [ ! -f /var/www/html/.env ]; then
  if [ -f /var/www/html/.env.example ]; then
    cp /var/www/html/.env.example /var/www/html/.env
  fi
fi

# Ensure APP_KEY is set
if ! grep -q "^APP_KEY=" /var/www/html/.env; then
  echo "APP_KEY=base64:$(openssl rand -base64 32)" >> /var/www/html/.env
fi

# Install composer dependencies
composer install

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Start PHP-FPM
php-fpm
