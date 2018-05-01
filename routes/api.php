<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->namespace('Api')->group(function ($router) {
    $router->get('/user', function (Request $request) {
        return $request->user();
    });

    $router->resource('users', 'UsersController')->only(['store', 'destroy']);
    $router->resource('groups', 'GroupsController')->only(['store', 'destroy']);
    $router->resource('groups.users', 'GroupUsersController')->only(['store', 'destroy']);
});
