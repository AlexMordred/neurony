<?php

Route::get('/', 'AdminController@index')->name('admin');
Route::delete('/{thread}', 'AdminController@destroy')->name('admin.destroy');
