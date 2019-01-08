<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
if ($options['register'] ?? true) {
    $this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    $this->post('register', 'Auth\RegisterController@register');
}

// Password Reset Routes...
// if ($options['reset'] ?? true) {
//     $this->resetPassword();
// }

Route::get('/', function () {
    return view('welcome');
})->middleware('guest')
    ->name('index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', 'ProfileController@show')->name('profile');

    Route::get('/threads', 'ThreadsController@index')->name('threads');
});
