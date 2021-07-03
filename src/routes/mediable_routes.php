<?php

use Illuminate\Support\Facades\Route;

Route::put('media/{medium}', 'MediaController@update')->name('media.update');
Route::delete('media/{medium}', 'MediaController@destroy')->name('media.destroy');
