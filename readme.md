Setup a DB and .env
```shell
DEV_NAME="Test"
DEV_EMAIL="test@gmail.com"
DEV_PASSWORD="secret"
ADMIN_PREFIX="admin"
```
In the root of you project run:
```shell
$ composer install
$ php artisan key:generate
$ php artisan storage:link
$ php artisan clearcaches
$ php artisan cleanuploads
$ php artisan droptables
$ php artisan migrate
$ php artisan db:seed
```
Login url
* for Users: '/login', '/register'
* for Admins: '<ADMIN_PREFIX>/login'

check laravel_test.sql for database
admin credentials
admin@mailinator.com
123456789
