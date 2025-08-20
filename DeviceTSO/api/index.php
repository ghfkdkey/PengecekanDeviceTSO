<?php

// Autoload composer dependencies
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap the Laravel application
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Handle the request
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Capture the request
$request = Illuminate\Http\Request::capture();

// Handle the request
$response = $kernel->handle($request);

// Send the response
$response->send();

// Terminate the kernel
$kernel->terminate($request, $response);