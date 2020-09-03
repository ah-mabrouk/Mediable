<?php

use Mabrouk\Mediablel\Http\Controllers\MediaController as MediableController;

Route::apiResource('media', MediableController::class, ['only', ['update', 'destroy']]);
