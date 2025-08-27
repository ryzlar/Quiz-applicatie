<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\ProfileController;

use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'index'])->name('index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    // Quiz overzicht
    Route::get('/quizzes', [MainController::class, 'quizzes'])->name('quizzes.index');

    Route::get('/quizzes/{quiz}/start', [MainController::class, 'startQuiz'])
        ->name('quizzes.start');
    Route::get('/quizzes/{quiz}/settings', [MainController::class, 'quizSettings'])
        ->name('quizzes.settings');

    // Alleen voor teacher: quiz aanmaken
    Route::get('/quizzes/create', [MainController::class, 'createQuiz'])->name('quizzes.create');
    Route::post('/quizzes', [MainController::class, 'storeQuiz'])->name('quizzes.store');
    Route::delete('/quizzes/{quiz}', [MainController::class, 'destroyQuiz'])->name('quizzes.destroy');

    Route::get('/questions/{question}/edit', [MainController::class, 'queEdit'])->name('questions.edit');
    Route::put('/questions/{question}', [MainController::class, 'queUpdate'])->name('questions.update');
    Route::delete('/questions/{question}', [MainController::class, 'quesDestroy'])->name('questions.destroy');

});

require __DIR__.'/auth.php';
