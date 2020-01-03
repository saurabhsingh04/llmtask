docker-compose build --no-cache
docker-compose up -d
winpty docker-compose exec app php artisan migrate
winpty docker-compose exec app ./vendor/bin/phpunit