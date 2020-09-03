<?php

use Illuminate\Support\Facades\Route;

Route::post('pre-signed-url', 'Austinw\VideoTranscoder\Http\Controllers\PreSignController@store');
Route::post('videos', 'Austinw\VideoTranscoder\Http\Controllers\VideoController@store');
