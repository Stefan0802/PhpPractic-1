<?php

use Src\Route;

Route::add('GET', '/', [Controller\Api::class, 'index']);
Route::add('POST', '/echo', [Controller\Api::class, 'echo']);

Route::add('POST', '/register', [Controller\User::class, 'api_register']);
Route::add('POST', '/login', [Controller\User::class, 'api_login']);
Route::add('GET', '/home', [Controller\Api::class, 'home']);
Route::add('GET', '/logout', [Controller\User::class, 'logout']);
//->middleware('token')
Route::add('GET', '/about', [Controller\Api::class, 'about']);

Route::add('POST', '/createPhone', [Controller\Phone::class, 'create_number']);

Route::add('GET', '/phone', [Controller\Phone::class, 'phone']);


