cp src/.env.example src/.env
docker-compose build --no-cache
docker-compose up -d
docker-compose exec app php artisan migrate
docker-compose exec app ./vendor/bin/phpunit