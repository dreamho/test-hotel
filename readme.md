Quantox Hotel

Requirements
1. PHP (versions PHP >= 7.1.3)
2. Apache
3. Mysql
4. Composer
5. Enabled mod_rewrite

Installing
1. git clone https://github.com/dreamho/quantox-hotel.git
2. sudo cp .env.example .env 
3. set up database credentials in .env file
4. composer install
5. php artisan key:generate
6. php artisan migrate --seed
7. php artisan serve
  
Setting up JWT Token-based Authentication
1. composer require tymon/jwt-auth
2. In the config/app.php add the following lines
    'providers' => [
    
        'Tymon\JWTAuth\Providers\JWTAuthServiceProvider',
    ],
    'aliases' => [
    
        'JWTAuth' => 'Tymon\JWTAuth\Facades\JWTAuth',
        'JWTFactory' => 'Tymon\JWTAuth\Facades\JWTFactory',
    ]
3. php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\JWTAuthServiceProvider"
4. php artisan jwt:generate


Implemented functionalities:
1. Register and login system for users with multiple roles base on JWT Authentication
2. Only users with certain roles(admin and dj) can implement CRUD methods on songs