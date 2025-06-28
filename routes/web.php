<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Core\Presentation\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', function() {
    return redirect(config('services.auth.google.loginUrl'));
})->name('login');

Route::middleware('auth:web')->group(function () {
    Route::get('/dashboard', function (Request $request) {
        return 'Dashboard';
    });
});
