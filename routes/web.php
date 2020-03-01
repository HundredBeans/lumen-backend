<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Generate Application Key
$router->get('/key', 'ExampleController@generateKey');
// Admin 
$router->post('/add', 'AdminController@insertCd');
$router->put('/cd/{id}', 'AdminController@editCd');
$router->delete('/cd/{id}', 'AdminController@deleteCd');
$router->get('/rent/returned', 'AdminController@getListRentReturned');
$router->get('/rent/notreturned', 'AdminController@getListRentNotReturned');

// User
$router->get('/cd', 'UserController@getListCd');
$router->get('/cd/{id}', 'UserController@getCd');
$router->post('/cd/{id}/rent', 'UserController@rentCd');
$router->get('/user/rent', 'UserController@checkListUserRent');
$router->get('/user/rent/{id}', 'UserController@checkUserRent');
$router->post('/user/return/{id}', 'UserController@returnUserRent');

// Auth
$router->post('/register', 'AuthController@register');
$router->post('/login', 'AuthController@login');