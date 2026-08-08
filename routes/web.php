<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InspirationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuizReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/inspiration/{score}', [InspirationController::class, 'index'])->name('inspiration');
    Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');
    Route::get('/quiz/retake', [QuizController::class, 'retake'])->name('quiz.retake');
    Route::post('/quiz/retake', [QuizController::class, 'retakeSubmit'])->name('quiz.retake.submit');
    Route::get('/quiz/review', [QuizController::class, 'review'])->name('quiz.review');
    Route::get('/review', [QuizReviewController::class, 'index'])->name('review');
    Route::post('/quiz/submit', [QuizController::class, 'submit'])->name('quiz.submit');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
