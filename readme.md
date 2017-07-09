## Setup project

composer install

chmod 777 -R storage

php artisan migrate status

php artisan migrate

php artisan passport:keys

php artisan passport:client --personal

php artisan storage:link

php artisan webpush:vapid

php artisan db:seed --class=RolesTableSeeder

php artisan db:seed --class=PlatformsTableSeeder

npm run dev

php artisan queue:work database --queue=lessons