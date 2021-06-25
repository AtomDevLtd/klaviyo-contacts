## Requirements

PHP >= 7.4,
node 
npm
composer
Mysql
Redis

## Setup

1. composer install
2. npm install && npm run dev
3. create DB 
4. create Klaviyo account and get your private api_key
5. KLAVIYO_ACCOUNT_KEY={the api_key klaviyo}
   CACHE_DRIVER=redis
   QUEUE_CONNECTION=redis
   SESSION_DRIVER=redis
   SESSION_LIFETIME=1440
6. php artisan migrate   
7. php artisan horizon 



