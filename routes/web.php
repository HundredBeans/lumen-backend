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
// Get all cd
$router->get('/cd', 'UserController@index');
// Insert new cd
$router->post('/cd/add', 'UserController@insertCd');
// Edit selected cd
$router->put('/cd/{id}', 'UserController@editCd');
// Get selected cd
$router->get('/cd/{id}', 'UserController@getCd');
// Search
$router->get('/search', 'UserController@searchCd');

$router->get('/foo', function() {
    return 'Hello, GET method!';
});

$router->get('/user/{id}', function($id) {
    return 'User ID ='.$id;
});

$router->get('/optional[/{param}]', function($param = null) {
    return 'Optional = '.$param;
});

// Redirect
$router->get('profile/redirect', ['as' => 'route.profile', function() {
    return 'Route Profile';
}]);

$router->get('redirect', function() {
    return redirect()->route('route.profile');
});

$router->get('fail', function() {
    return 'FAIL NOT ENOUGH AGE LOLLL';
});

// Route Group
$router->group(['prefix' => 'admin', 'middleware' => 'age'], function() use ($router) {
    $router->get('home', function() {
        return 'Home Admin';
    });

    $router->get('login', function() {
        return 'Admin Login';
    });

    $router->get('', function() {
        return 'Admin Page';
    });
});