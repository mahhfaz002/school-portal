release: php artisan migrate --force && php artisan storage:link && php artisan config:cache && php artisan route:cache
web: vendor/bin/heroku-php-apache2 public/
