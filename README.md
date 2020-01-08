# <p align="center">LLMTASK</p>
# Getting started

## Requirements

- [Docker](https://www.docker.com/) as the container service to isolate the environment.
- [Php](https://php.net/) to develop backend support.
- [Laravel](https://laravel.com) as the server framework / controller layer
- [MySQL](https://mysql.com/) as the database layer
- [NGINX](https://docs.nginx.com/nginx/admin-guide/content-cache/content-caching/) as a proxy / content-caching layer

## Running through docker

Clone the repository

    git clone https://github.com/saurabhsingh04/llmtask

Switch to the repo folder

    cd llmtask

**Make sure you set the correct distance API key in the file src/.env by updating the DISTANCE_API_KEY variable value**

For creating image and start the container.

	./start.sh
After starting container following will be executed automatically:

- Table migrations using artisan migrate command.
- Api Documentation
- Unit and Integration test cases execution.

## Testing API Manually

Run unit and integration test

	docker-compose exec app ./vendor/bin/phpunit

## Generate API Documentation Manually

	docker-compose exec app php artisan l5-swagger:generate

## API Documentation

	http://localhost/documentation
