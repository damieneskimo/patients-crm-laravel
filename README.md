## Brief
This project uses Laravel(8.12) to build API for managing patients. Its sibling frontend Vue SPA project is used to consume the API (https://github.com/damieneskimo/patients-crm-vue-spa).

<br />

For authentication, this project uses Laravel Sanctum (https://laravel.com/docs/8.x/sanctum). Please see the .env.example for example settings. Since Sanctum is a cookie based session authentication service. it's important to note that the backend domain and front end domain should be under the same top domain. The eaisest way is probably to use localhost with different ports: eg. Laravel backend: `php artisan serve`, which opens port 8000; Vue SPA uses port 8080 or React uses port 3000. If you use some virtual environment like Vagrant, please change accordingly.

## Project Setup
1. clone the project and run it
```
composer install
php artisan migrate
php artisan db:seed  (this will seed 1 admin user, 1 normal user and 50 patients with each having 5 notes)
php artisan serve
```

2. create a admin user to login

```
php artisan admin:create
(enter name, email and password as the console command prompts.)
```
