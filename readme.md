## About PhoneBook

PhoneBook.

## How to setup PhoneBook
- Run composer install
- Run npm install
- Copy .env.example to .env and change database information
- Run php artisan key:generate
- Run php artisan migrate

## Run environment
- Run php artisan serve --port=6789 (if use other port)

## Development
- Create new model with migration and resource controller: php artisan make:model Models\name_model -mrc
- Create new model with migration and empty controller: php artisan make:model Models\name_model -mc
- Create new model with migration: php artisan make:model Models\name_model -m
- Create new controller with resource: php artisan make:controller name_folder\name_controller -r
- Create new controller for api: php artisan make:controller name_folder\name_controller --api
- Create new migration for new table: php artisan make:migration create_name_table --create=name
- Create new migration for update table: php artisan make:migration create_name_table --table=name_table