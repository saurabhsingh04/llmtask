docker-compose down -v
docker-compose build --no-cache
docker-compose up -d
sleep 15
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan l5-swagger:generate
docker-compose exec app ./vendor/bin/phpunit
docker-compose exec app bash -c "chmod -R 777 storage"