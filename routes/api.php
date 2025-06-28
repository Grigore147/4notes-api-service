<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

# Group routes with middleware
Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
