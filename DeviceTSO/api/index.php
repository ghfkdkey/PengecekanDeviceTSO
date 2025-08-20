<?php

// Set memory limit
ini_set('memory_limit', '256M');

// Autoload composer dependencies
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
} else {
    die('Composer dependencies not found. Please run composer install.');
}

// Bootstrap the Laravel application
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Create request from globals
$request = Illuminate\Http\Request::capture();

// Handle the request through Laravel
$response = $app->handle($request);

// Send the response
$response->send();

// Terminate the application
$app->terminate($request, $response);