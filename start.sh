docker-compose down -v
docker-compose build --no-cache
docker-compose up -d
sleep 15
docker-compose exec app php artisan migrate
docker-compose exec app ./vendor/bin/phpunit