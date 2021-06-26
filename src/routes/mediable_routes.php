<?php

use Illuminate\Routing\Route;

Route::put('media', 'MediaController@update')->name('media.update');
Route::delete('media', 'MediaController@destroy')->name('media.destroy');
