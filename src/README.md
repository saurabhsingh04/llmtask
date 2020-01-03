#<p align="center">LLMTASK</p>
# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/6.x/installation#installation)


Clone the repository

    git clone llmtask.git

Switch to the repo folder

    cd llmtask/src

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

**command list**

    git clone llmtask.git
    cd llmtask/src
    composer install
    cp .env.example .env
	php artisan l5-swagger:generate
**Make sure you set the correct database connection information before running the migrations and api documentation** [Environment variables](#environment-variables)

    php artisan migrate
    php artisan serve

## Database seeding

**Populate the database with seed orders data. This can help you to quickly start testing the api with ready content.**

Open the OrdersTableSeeder and set the property values as per your requirement

    database/seeds/OrdersTableSeeder.php

Run the database seeder and you're done

     php artisan db:seed --class=OrdersTableSeeder

***Note*** : It's recommended to have a clean database before seeding. You can refresh your migrations at any point to clean the database by running the following command

    php artisan migrate:refresh

## API Specification

This application adheres to the api documentation

	{{L5_SWAGGER_BASE_PATH}}/api/documentation

----------

# Code overview

## Dependencies

- [L5-Swagger](https://github.com/DarkaOnLine/L5-Swagger) - For OpenApi or Swagger Specification of APIs
- [guzzle](https://github.com/guzzle/guzzle) - For send HTTP requests and trivial to integrate with web services.

## Folders

- `app/Http/Model` - Contains all the Eloquent models
- `app/Http/Controllers/Api` - Contains all the api controllers
- `app/Http/Exception` - Contains the Exception handler for APIs
- `app/Http/Contracts` - Contains all the api interface
- `app/Http/Repositories` - Contains all the model Repositories
- `app/Htttp/Services` - Contains the services
- `config` - Contains all the application configuration files
- `database/factories` - Contains the model factory for all the models
- `database/migrations` - Contains all the database migrations
- `database/seeds` - Contains the database seeder
- `routes` - Contains all the api routes defined in api.php file
- `tests` - Contains all the application tests

## Environment variables

- `.env` - Environment variables can be set in this file
- `DISTANCE_API_KEY` - Set the google map api key to get distnace.
- `DISTANCE_API_URL` - Set the API url to get the distance.
- `L5_SWAGGER_BASE_PATH` - Set the base path of api documentation.

***Note*** : You can quickly set the database information and other variables in this file and have the application fully working.

----------

# Testing API
Run the Unit test

	./vendor/bin/phpunit

Run the laravel development server

    php artisan serve

The api can now be accessed at

    http://localhost:8000/api