<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$app->group(['middleware' => 'requireAuth'], function($app)
{
    /*
     | Auths
     */
    $app->get('auth/logout', ['as' => 'logout', function(Request $rqst) {
        Auth::logout();
        return redirect()->route('login')->with('logged_out', true);
    }]);

    /*
     | Binary categories
     */
    $app->delete ('binaries/categories/{id}', 'App\Http\Controllers\BinaryCategoryController@delete');
    $app->get    ('binaries/categories/{id}', 'App\Http\Controllers\BinaryCategoryController@get');
    $app->options('binaries/categories/{id}', 'App\Http\Controllers\BinaryCategoryController@optionsId');
    $app->put    ('binaries/categories/{id}', 'App\Http\Controllers\BinaryCategoryController@update');
    $app->get    ('binaries/categories',      'App\Http\Controllers\BinaryCategoryController@getAll');
    $app->options('binaries/categories',      'App\Http\Controllers\BinaryCategoryController@optionsAll');
    $app->post   ('binaries/categories',      'App\Http\Controllers\BinaryCategoryController@create');

    /*
     | Binary versions
     */
    $app->delete ('binaries/versions/{id}', 'App\Http\Controllers\BinaryVersionController@delete');
    $app->get    ('binaries/versions/{id}', 'App\Http\Controllers\BinaryVersionController@get');
    $app->options('binaries/versions/{id}', 'App\Http\Controllers\BinaryVersionController@optionsId');
    $app->put    ('binaries/versions/{id}', 'App\Http\Controllers\BinaryVersionController@update');
    $app->get    ('binaries/versions',      'App\Http\Controllers\BinaryVersionController@getAll');
    $app->options('binaries/versions',      'App\Http\Controllers\BinaryVersionController@optionsAll');
    $app->post   ('binaries/versions',      'App\Http\Controllers\BinaryVersionController@create');

    /*
     | Binaries
     */
    $app->delete ('binaries/{id}', 'App\Http\Controllers\BinaryController@delete');
    $app->get    ('binaries/{id}', 'App\Http\Controllers\BinaryController@get');
    $app->options('binaries/{id}', 'App\Http\Controllers\BinaryController@optionsId');
    $app->put    ('binaries/{id}', 'App\Http\Controllers\BinaryController@update');
    $app->get    ('binaries',      'App\Http\Controllers\BinaryController@getAll');
    $app->options('binaries',      'App\Http\Controllers\BinaryController@optionsAll');
    $app->post   ('binaries',      'App\Http\Controllers\BinaryController@create');

    /*
     | Roles
     */
    $app->delete ('roles/{id}', 'App\Http\Controllers\RoleController@delete');
    $app->get    ('roles/{id}', 'App\Http\Controllers\RoleController@get');
    $app->options('roles/{id}', 'App\Http\Controllers\RoleController@optionsId');
    $app->put    ('roles/{id}', 'App\Http\Controllers\RoleController@update');
    $app->get    ('roles',      'App\Http\Controllers\RoleController@getAll');
    $app->options('roles',      'App\Http\Controllers\RoleController@optionsAll');
    $app->post   ('roles',      'App\Http\Controllers\RoleController@create');

    /*
     | Servers
     */
    $app->delete ('servers/{id}', 'App\Http\Controllers\ServerController@delete');
    $app->get    ('servers/{id}', 'App\Http\Controllers\ServerController@get');
    $app->options('servers/{id}', 'App\Http\Controllers\ServerController@optionsId');
    $app->put    ('servers/{id}', 'App\Http\Controllers\ServerController@update');
    $app->get    ('servers',      'App\Http\Controllers\ServerController@getAll');
    $app->options('servers',      'App\Http\Controllers\ServerController@optionsAll');
    $app->post   ('servers',      'App\Http\Controllers\ServerController@create');

    /*
     | Users
     */
    $app->delete ('users/{id}', 'App\Http\Controllers\UserController@delete');
    $app->get    ('users/{id}', 'App\Http\Controllers\UserController@get');
    $app->options('users/{id}', 'App\Http\Controllers\UserController@optionsId');
    $app->put    ('users/{id}', 'App\Http\Controllers\UserController@update');
    $app->get    ('users',      'App\Http\Controllers\UserController@getAll');
    $app->options('users',      'App\Http\Controllers\UserController@optionsAll');
    $app->post   ('users',      'App\Http\Controllers\UserController@create');

    /*
     | Generics
     */
    $app->get('/', ['as' => 'dashboard', function() {
        if (view()->exists('app')) {
            return view('app');
        }
        return 'Application view not found. Make sure "resources/views/app.php" exists.';
    }]);
});

/*
 | Login
 */
$app->get('auth/login', ['as' => 'login', function() {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
}]);

$app->post('auth/login', function(Request $rqst) {
    $remember = $rqst->input('remember') === 'on' ? true : false;
    if (Auth::attempt($rqst->only('email', 'password'), $remember)) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login')->with('login_failed', true)->withInput();
});
