Setup a DB and .env
```shell
replace .env.example with your database credentials
In the root of you project run:
```shell
$ composer install
$ php artisan key:generate
$ php artisan storage:link
$ php artisan clearcaches
$ php artisan cleanuploads
$ php artisan db:seed
$ php artisan droptables
$ php artisan migrate

import laravel.sql for database
