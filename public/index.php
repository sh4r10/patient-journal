<?php

use Illuminate\Http\Request;
use App\Http\Middleware\RequestLogger;

define('LARAVEL_START', microtime(true));

// Step 1: Register the Composer autoloader
require __DIR__.'/../vendor/autoload.php';

// Step 2: Bootstrap Laravel application
$app = require_once __DIR__.'/../bootstrap/app.php';

// Step 3: Determine if the application is in maintenance mode
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Step 4: Capture the request
$request = Request::capture();

// Step 5: Manually apply middleware (RequestLogger)
$middleware = new RequestLogger();
$response = $middleware->handle($request, function ($req) use ($app) {
    // Use Laravel's HTTP Kernel to handle the request
    return $app->make(Illuminate\Contracts\Http\Kernel::class)->handle($req);
});

// Step 6: Send the response
$response->send();

// Step 7: Perform any final tasks for the application
$app->terminate($request, $response);
