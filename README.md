# Eden Life Superban

> A simple Laravel package use to limiting abstraction which, in conjunction with your application's cache, provides an easy way to limit any action during a specified window of time.

## Installation

[PHP](https://php.net) 8.1+ and [Composer](https://getcomposer.org) are required.

To get the latest version of the Laravel Paystack, simply require it

```bash
composer require edenlife/superban
```

## Usage

Superban support all laravel cache driver i.e file, redis, database etc..

## Usage

Open your .env file and change your `CACHE_DRIVER` to what ever driver you want like so:

```php
.....
CACHE_DRIVER=file
....
```
*Your cache driver configuration will be based on what driver you decide to make use of. [read more](https://laravel.com/docs/10.x/cache)* 

### Redis driver

If you decide to use the redis cache driver you will need to update your .env file as so. 

```bash 
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Database driver  

If you decide to use the database cache driver you will need to run the following command as below 

```bash 
php artisan cache:table  
php artisan migrate
```

## Middleware 

Apply the Superban middleware to group of routes using the following syntax:

```php

Route::middleware(['superban:200,2,1440'])->group(function () {
   Route::post('/thisroute', function () {
       //
   });
 
   Route::post('anotherroute', function () {
       //
   });
});

```

Also you can apply superban middleware to a particular route using the following syntax: 

```php 

 Route::get("/", function(){

 })->middlware(['superban:200,2,1440']);

```

All requests are taking into consideration which are `IP Address`, `User ID` and `User Email Address`

The middleware parameter can be explained in the table below:
| Options       | Detail                                                             | Example   |  
| ------------- | -------------------------------------------------------------------| --------------- | 
| Number of Requests   | Specifies the number of requests allowed                          | 200               |  
| Time Interval | Amount of minutes for the period of time the number of requests can happen    | 2               |  
| Ban duration   | Amount minutes for which the user will be banned for    | 1440              |   


If no parameter was passed in middleware like so `->middleware('superban')` i.e without specification the `number of request`, `time interval` and `ban duration`. The package will use the default configuration. 

You can also change this to your own desire like so

## Add Configuration

Publish the configuration file using this command:

```bash
php artisan vendor:publish --tag=superban
```

A configuration-file named `suberban.php` with some sensible defaults will be placed in your `config` directory:

```php
<?php

return [
     // Number of requests before triggering a ban
    'requests_number' => env("SUPERBAN_REQUEST_NUMBER", 200),
    // Duration of the ban in minutes
    'ban_duration' => env("SUPERBAN_DURATION", 1440),
     // in minutes
    'time_interval' => env("SUPERBAN_TIME_INTERVAL", 2),
];

```

Now, you can change to your own desire.

## Contributing

Please feel free to fork this package and contribute by submitting a pull request to enhance the functionalities.

## You need an example?

Check out the example directory on how to use this package

Thanks!
Benson Arafat.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
