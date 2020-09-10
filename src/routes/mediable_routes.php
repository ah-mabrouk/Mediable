<?php

Route::apiResource('media', MediaController::class, ['only', ['update', 'destroy']]);
