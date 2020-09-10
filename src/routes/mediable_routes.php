<?php

Route::group(['namespace' => 'Mabrouk\Mediablel\Http\Controllers'], function()
{
    Route::apiResource('media', MediaController::class, ['only', ['update', 'destroy']]);
});
