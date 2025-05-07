<?php

use Src\Route;

Route::add('GET', '/', [Controller\Api::class, 'index']);
Route::add('POST', '/echo', [Controller\Api::class, 'echo']);

Route::add('POST', '/register', [Controller\Api::class, 'api_register']);
Route::add('POST', '/login', [Controller\Api::class, 'api_login']);
Route::add('GET', '/home', [Controller\Api::class, 'home'])->middleware('token');

Route::add('GET', '/about', [Controller\Api::class, 'about'])->middleware('token');

Route::add('POST', '/createPhone', [Controller\Api::class, 'create_number'])->middleware('token');

Route::add('GET', '/phone', [Controller\Api::class, 'phone'])->middleware('token');


