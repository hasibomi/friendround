# FriendRound
Friend Round is a social networking API developed for coding test for Chhuti.

### Package used
1. [JWT](https://github.com/tymondesigns/jwt-auth)
2. [Laravel CROS](https://github.com/barryvdh/laravel-cors)

### Installation Prerequisite
1. PHP >= 7.1.3
2. MySQL
3. [Composer](https://getcomposer.org)

### Installation
1. Clone the repo
2. Run `composer install`
3. Make `.env` file & copy-paste the contents from `.env.example`
4. Change `APP_NAME`, `APP_URL`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
5. Run `php artisan key:generate`
6. Run `php artisan serve` & visit `http://localhost:8000` on your browser
