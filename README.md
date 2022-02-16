## Run below command after cloning the repository.
- To clone the repository execute this command.
- `git clone https://github.com/patelpriyangu/laravel-app-api`

To move into cloned directory.
- `cd laravel-app-api`

## Set database environment in env file.
Copy .env.example file and create .env file in root directory.

set database name in .env file below mentioned parameters. 

- 
DB_DATABASE=laravel

DB_USERNAME=root

DB_PASSWORD=


To install vendor packages execute below command. 
- `composer install`

To create database table after configuring env file for database. Execute below mentioned command.
- `php artisan migrate`

To insert example data into the database. Execute seeding command.
- `php artisan db:seed`


## Postman Collection and API Documentation in postman link.

Postman Json Collection Link
https://www.getpostman.com/collections/8d231780aeec81e872b2


Published Document
https://documenter.getpostman.com/view/3763224/UVkgwdys


## Now move to postman and set the base_url in variable.

Execute register api to create new customer. 
{{base_url}}/register

Then execute login api

{{base_url}}/login
The response of api will provide bearer access token on successful validation of username passowrd.

## To execute feauture test case please run below mentioned command.
- `php artisan test --testsuite=Feature`



