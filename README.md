# <p align="center">LLMTASK</p>
# Getting started

## Running through docker

Clone the repository

    git clone https://github.com/saurabhsingh04/llmtask

Switch to the repo folder

    cd llmtask

**Make sure you set the correct distance API key in the file src/.env by updating the DISTANCE_API_KEY variable value**

For creating container, running test cases and generating documentation  run the

	./start.sh

## Testing API

Run unit and integration test

	docker-compose exec app ./vendor/bin/phpunit

## Generate API Documentation

	docker-compose exec app php artisan l5-swagger:generate

## API Documentation

	http://localhost/api/documentation
