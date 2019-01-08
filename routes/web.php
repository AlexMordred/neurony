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

    // Threads
    Route::get('/threads', 'ThreadsController@index')->name('threads');
    Route::post('/threads', 'ThreadsController@store')->name('threads.store');
    Route::put('/threads/{thread}', 'ThreadsController@update')->name('threads.update');
    Route::delete('/threads/{thread}', 'ThreadsController@destroy')->name('threads.destroy');
    
    Route::get('/threads/create', 'ThreadsController@create')->name('threads.create');
    Route::get('/threads/edit/{thread}', 'ThreadsController@edit')->name('threads.edit');
    Route::get('/threads/{thread}', 'ThreadsController@show')->name('threads.show');

    // Thread replies
    Route::get('/threads/{thread}/replies', 'ThreadRepliesController@index')->name('threads.replies');
    Route::post('/threads/{thread}/replies', 'ThreadRepliesController@store')->name('threads.replies.store');
});
