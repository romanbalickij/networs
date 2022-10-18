#!/bin/sh
composer config -g github-oauth.github.com $GITHUB_TOKEN

if [ ! -d /backend/vendor ]; then
  composer install --no-interaction --ignore-platform-reqs
fi

php artisan key:generate
php artisan migrate
# php artisan db:seed



