## Setup project

composer install

chmod 777 -R storage

php artisan migrate status

php artisan migrate

php artisan passport:keys

php artisan passport:client --personal

php artisan storage:link

npm run dev