## Setup project

composer install

npm install

chmod 777 -R storage

'''
Após instalar se der o erro: Class 'Cmgmyr\Messenger\MessengerServiceProvider' not found 
Execitar: composer require cmgmyr/messenger
Executar: php artisan vendor:publish --provider="Cmgmyr\Messenger\MessengerServiceProvider"
'''

php artisan migrate status

php artisan migrate

php artisan passport:keys

php artisan passport:client --personal

php artisan storage:link

php artisan webpush:vapid

php artisan db:seed --class=RolesTableSeeder
 
php artisan db:seed --class=PlatformsTableSeeder

php artisan db:seed --class-BillingParamSeeder

php artisan app:user:admin:create

npm run dev

php artisan queue:work database --queue=email,lessons,user,notification,default,chat  --tries=3