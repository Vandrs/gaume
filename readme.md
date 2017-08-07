## Setup project

composer install

npm install

chmod 777 -R storage

php artisan migrate status

php artisan migrate

php artisan passport:keys

php artisan passport:client --personal

php artisan storage:link

php artisan webpush:vapid

php artisan db:seed --class=RolesTableSeeder

php artisan db:seed --class=PlatformsTableSeeder

php artisan user:admin:create

npm run dev

php artisan queue:work database --queue=lessons
php artisan queue:work database --queue=email
php artisan queue:work database --queue=user