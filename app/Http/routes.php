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

/* Authentication */
$app->group(['prefix' => 'auth'], function ($app) {
    $app->get('login', ['as' => 'login',
        'uses' => 'App\Http\Controllers\AuthenticationController@showLogin'
    ]);
    $app->post('login', [
        'uses' => 'App\Http\Controllers\AuthenticationController@login'
    ]);
    $app->get('logout', ['as' => 'logout',
        'uses' => 'App\Http\Controllers\AuthenticationController@logout'
    ]);
});

/* Binary Categories */
$app->group(['prefix' => 'binaries/categories', 'middleware' => 'forceLoggedIn'], function ($app) {
    $app->delete ('{id}', 'App\Http\Controllers\BinaryCategoryController@deleteById');
    $app->get    ('{id}', 'App\Http\Controllers\BinaryCategoryController@getById');
    $app->options('{id}', 'App\Http\Controllers\BinaryCategoryController@optionsForId');
    $app->put    ('{id}', 'App\Http\Controllers\BinaryCategoryController@putById');
    $app->get    ('',     'App\Http\Controllers\BinaryCategoryController@getAll');
    $app->options('',     'App\Http\Controllers\BinaryCategoryController@optionsForAll');
    $app->post   ('',     'App\Http\Controllers\BinaryCategoryController@create');
});

/* Binary Versions Gatherers */
$app->group(['prefix' => 'binaries/versions/gatherers', 'middleware' => 'forceLoggedIn'], function ($app) {
    $app->get('', 'App\Http\Controllers\BinaryVersionGathererController@getAll');
});

/* Binary Versions */
$app->group(['prefix' => 'binaries/versions', 'middleware' => 'forceLoggedIn'], function ($app) {
    $app->delete ('{id}', 'App\Http\Controllers\BinaryVersionController@deleteById');
    $app->get    ('{id}', 'App\Http\Controllers\BinaryVersionController@getById');
    $app->options('{id}', 'App\Http\Controllers\BinaryVersionController@optionsForId');
    $app->put    ('{id}', 'App\Http\Controllers\BinaryVersionController@putById');
    $app->get    ('',     'App\Http\Controllers\BinaryVersionController@getAll');
    $app->options('',     'App\Http\Controllers\BinaryVersionController@optionsForAll');
    $app->post   ('',     'App\Http\Controllers\BinaryVersionController@create');
});

/* Binaries */
$app->group(['prefix' => 'binaries', 'middleware' => 'forceLoggedIn'], function ($app) {
    $app->delete ('{id}', 'App\Http\Controllers\BinaryController@deleteById');
    $app->get    ('{id}', 'App\Http\Controllers\BinaryController@getById');
    $app->options('{id}', 'App\Http\Controllers\BinaryController@optionsForId');
    $app->put    ('{id}', 'App\Http\Controllers\BinaryController@putById');
    $app->get    ('',     'App\Http\Controllers\BinaryController@getAll');
    $app->options('',     'App\Http\Controllers\BinaryController@optionsForAll');
    $app->post   ('',     'App\Http\Controllers\BinaryController@create');
});

/* Messages */
$app->group(['prefix' => 'messages', 'middleware' => 'forceLoggedIn'], function ($app) {
    $app->delete ('{id}', 'App\Http\Controllers\MessageController@deleteById');
    $app->get    ('{id}', 'App\Http\Controllers\MessageController@getById');
    $app->get    ('',     'App\Http\Controllers\MessageController@getAll');
});

/* Roles */
$app->group(['prefix' => 'roles', 'middleware' => 'forceLoggedIn'], function ($app) {
    $app->delete ('{id}', 'App\Http\Controllers\RoleController@deleteById');
    $app->get    ('{id}', 'App\Http\Controllers\RoleController@getById');
    $app->options('{id}', 'App\Http\Controllers\RoleController@optionsForId');
    $app->put    ('{id}', 'App\Http\Controllers\RoleController@putById');
    $app->get    ('',     'App\Http\Controllers\RoleController@getAll');
    $app->options('',     'App\Http\Controllers\RoleController@optionsForAll');
    $app->post   ('',     'App\Http\Controllers\RoleController@create');
});

/* Servers */
$app->group(['prefix' => 'servers', 'middleware' => 'forceLoggedIn'], function ($app) {
    $app->delete ('{id}', 'App\Http\Controllers\ServerController@deleteById');
    $app->get    ('{id}', 'App\Http\Controllers\ServerController@getById');
    $app->options('{id}', 'App\Http\Controllers\ServerController@optionsForId');
    $app->put    ('{id}', 'App\Http\Controllers\ServerController@putById');
    $app->get    ('',     'App\Http\Controllers\ServerController@getAll');
    $app->options('',     'App\Http\Controllers\ServerController@optionsForAll');
    $app->post   ('',     'App\Http\Controllers\ServerController@create');
});

/* Users */
$app->group(['prefix' => 'users', 'middleware' => 'forceLoggedIn'], function ($app) {
    $app->delete ('{id}', 'App\Http\Controllers\UserController@deleteById');
    $app->get    ('{id}', 'App\Http\Controllers\UserController@getById');
    $app->options('{id}', 'App\Http\Controllers\UserController@optionsForId');
    $app->put    ('{id}', 'App\Http\Controllers\UserController@putById');
    $app->get    ('',     'App\Http\Controllers\UserController@getAll');
    $app->options('',     'App\Http\Controllers\UserController@optionsForAll');
    $app->post   ('',     'App\Http\Controllers\UserController@create');
});

/* Generics */
$app->group(['middleware' => 'forceLoggedIn'], function ($app) {
    $app->get('/', ['as' => 'dashboard', function () {
        if (view()->exists('app')) {
            return view('app')->with('user', Auth::user());
        }
        return 'Application view not found. Make sure "resources/views/app.blade.php" exists.';
    }]);
});
