#bash

copy .env.example .env
composer install
php artisan key:generate
php artisan migrate:fresh --seed

sudo chgrp -R www-data storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache