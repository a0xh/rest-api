<?php

use Illuminate\Support\Facades\Route;
use App\Controllers\Api\V1\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('test', [AuthController::class, 'test']);
