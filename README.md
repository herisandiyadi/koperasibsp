# Project Title

BSP Koperasi Application with laravel base framework.
> *Tell our clients that we are managing our projects professionally.*

## Getting Started

BSP Koperasi was built for easy and professional project management.

## Getting Started
This application can be installed on local server and online server with these specifications :

#### Server Requirements
1. PHP 7.0 (and meet [Laravel 5.7 server requirements](https://laravel.com/docs/5.5#server-requirements)),
2. MySQL or MariaDB database,
3. SQlite (for automated testing).

#### Installation Steps

1. Clone the repo : `git clone https://gitlab.com/kangyasin/bspkoperasi.git`
2. `$ cd bspkoperasi`
3. `$ composer install`
4. `$ cp .env.example .env`
5. `$ php artisan key:generate`
6. Create new MySQL database for this application
7. Set database credentials on `.env` file
8. `$ php artisan migrate`
9. `$ php artisan laravolt:indonesia:seed`
10. `$ php artisan db:seed`
11. `$ npm install`
12. `$ npm run dev`
13. download kendo-ui and extract then move it to Public/vendor : `https://www.dropbox.com/sh/j4asaou30tu0bxu/AAD3zsQJdJFNMgjoGdrxyDa6a?dl=0` 
14. `$ php artisan serve`
12. Visit `http://localhost:8000` via web browser
13. Done, the application is ready to use.