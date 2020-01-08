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


## API Reference Documentation
- `localhost/orders?page=:page&limit=:limit` :

    GET Method - to fetch orders with page number and limit
    1. Header :
        - GET /orders?page=1&limit=5 HTTP/1.1
        - Host: localhost
        - Content-Type: application/json

    2. Responses :

    ```
            [
              {
                "id": 1,
                "distance": 2023,
                "status": "TAKEN"
              },
              ...
            ]
    ```

        Code                    Description
        - 200                   successful operation
        - 400                   Invalid Request Parameter

- `localhost/orders` :

    POST Method - to create new order with origin and distination
    1. Header :
        - POST /orders HTTP/1.1
        - Host: localhost
        - Content-Type: application/json

    2. Post-Data :
    ```
         {
            "origin" :["27.514501", "77.102493"],
            "destination" :["27.515517", "77.102513"]
         }
    ```

    3. Responses :
    ```
            {
              "id": 7601,
              "distance": 3000,
              "status": "UNASSIGNED"
            }
    ```

        Code                    Description
        - 200                   successful operation
        - 400                   BAD Request
        - 503                   Google distance service error
        - 500					Internal error

- `localhost/orders/:id` :

    PATCH method to update status for taken.
    1. Header :
        - PATCH /orders/2 HTTP/1.1
        - Host: localhost
        - Content-Type: application/json
    2. Post-Data :
    ```
         {
            "status" : "TAKEN"
         }
    ```

    3. Responses :
    ```
            {
              "status": "SUCCESS"
            }
    ```

        Code                    Description
        - 200                   successful operation
        - 400                   Invalid Request Parameter
        - 409                   Order already taken
        - 404                   Order id not found
