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
    # TODO: for each :: options, create, delete and put
    $app->get('binaries/categories/{id}/binaries', 'App\Http\Controllers\BinaryCategoryController@getBinariesForId');

    $app->delete ('binaries/categories/{id}', 'App\Http\Controllers\BinaryCategoryController@deleteById');
    $app->get    ('binaries/categories/{id}', 'App\Http\Controllers\BinaryCategoryController@getById');
    $app->options('binaries/categories/{id}', 'App\Http\Controllers\BinaryCategoryController@optionsForId');
    $app->put    ('binaries/categories/{id}', 'App\Http\Controllers\BinaryCategoryController@putById');
    $app->get    ('binaries/categories',      'App\Http\Controllers\BinaryCategoryController@getAll');
    $app->options('binaries/categories',      'App\Http\Controllers\BinaryCategoryController@optionsForAll');
    $app->post   ('binaries/categories',      'App\Http\Controllers\BinaryCategoryController@create');

    /*
     | Binary versions
     */
    # TODO: for each :: options, create, delete and put
    $app->get('binaries/versions/{id}/servers', 'App\Http\Controllers\BinaryVersionController@getServersForId');

    $app->delete ('binaries/versions/{id}', 'App\Http\Controllers\BinaryVersionController@deleteById');
    $app->get    ('binaries/versions/{id}', 'App\Http\Controllers\BinaryVersionController@getById');
    $app->options('binaries/versions/{id}', 'App\Http\Controllers\BinaryVersionController@optionsForId');
    $app->put    ('binaries/versions/{id}', 'App\Http\Controllers\BinaryVersionController@putById');
    $app->get    ('binaries/versions',      'App\Http\Controllers\BinaryVersionController@getAll');
    $app->options('binaries/versions',      'App\Http\Controllers\BinaryVersionController@optionsForAll');
    $app->post   ('binaries/versions',      'App\Http\Controllers\BinaryVersionController@create');

    /*
     | Binaries
     */
    # TODO: for each :: options, create, delete and put
    $app->get('binaries/{id}/categories', 'App\Http\Controllers\BinaryController@getCategoriesForId');
    $app->get('binaries/{id}/versions',   'App\Http\Controllers\BinaryController@getVersionsForId');

    $app->delete ('binaries/{id}', 'App\Http\Controllers\BinaryController@deleteById');
    $app->get    ('binaries/{id}', 'App\Http\Controllers\BinaryController@getById');
    $app->options('binaries/{id}', 'App\Http\Controllers\BinaryController@optionsForId');
    $app->put    ('binaries/{id}', 'App\Http\Controllers\BinaryController@putById');
    $app->get    ('binaries',      'App\Http\Controllers\BinaryController@getAll');
    $app->options('binaries',      'App\Http\Controllers\BinaryController@optionsForAll');
    $app->post   ('binaries',      'App\Http\Controllers\BinaryController@create');

    /*
     | Roles
     */
    # TODO: for each :: options, create, delete and put
    $app->get('roles/{id}/users', 'App\Http\Controllers\RoleController@getUsersForId');

    $app->delete ('roles/{id}', 'App\Http\Controllers\RoleController@deleteById');
    $app->get    ('roles/{id}', 'App\Http\Controllers\RoleController@getById');
    $app->options('roles/{id}', 'App\Http\Controllers\RoleController@optionsForId');
    $app->put    ('roles/{id}', 'App\Http\Controllers\RoleController@putById');
    $app->get    ('roles',      'App\Http\Controllers\RoleController@getAll');
    $app->options('roles',      'App\Http\Controllers\RoleController@optionsForAll');
    $app->post   ('roles',      'App\Http\Controllers\RoleController@create');

    /*
     | Servers
     */
    # TODO: for each :: options, create, delete and put
    $app->get('servers/{id}/binaries', 'App\Http\Controllers\ServerController@getBinariesForId');

    $app->delete ('servers/{id}', 'App\Http\Controllers\ServerController@deleteById');
    $app->get    ('servers/{id}', 'App\Http\Controllers\ServerController@getById');
    $app->options('servers/{id}', 'App\Http\Controllers\ServerController@optionsForId');
    $app->put    ('servers/{id}', 'App\Http\Controllers\ServerController@putById');
    $app->get    ('servers',      'App\Http\Controllers\ServerController@getAll');
    $app->options('servers',      'App\Http\Controllers\ServerController@optionsForAll');
    $app->post   ('servers',      'App\Http\Controllers\ServerController@create');

    /*
     | Users
     */
    # TODO: for each :: options, create, delete and put
    $app->get('users/{id}/binaries', 'App\Http\Controllers\UserController@getBinariesForId');
    $app->get('users/{id}/roles',    'App\Http\Controllers\UserController@getRolesForId');
    $app->get('users/{id}/servers',  'App\Http\Controllers\UserController@getServersForId');

    $app->delete ('users/{id}', 'App\Http\Controllers\UserController@deleteById');
    $app->get    ('users/{id}', 'App\Http\Controllers\UserController@getById');
    $app->options('users/{id}', 'App\Http\Controllers\UserController@optionsForId');
    $app->put    ('users/{id}', 'App\Http\Controllers\UserController@putById');
    $app->get    ('users',      'App\Http\Controllers\UserController@getAll');
    $app->options('users',      'App\Http\Controllers\UserController@optionsForAll');
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
    return redirect()->route('login')->with('login_failed', true)->with('email', $rqst->input('email'));
});
