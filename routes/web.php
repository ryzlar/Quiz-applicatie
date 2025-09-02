<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'index'])->name('index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // ========================
    // Quiz overzicht
    // ========================
    Route::get('/quizzes', [MainController::class, 'quizzes'])->name('quizzes.index');

    // ========================
    // Quiz starten
    // ========================
    // Form om vraag-types te kiezen (GET)
    Route::get('/quizzes/{quiz}/start', [MainController::class, 'startQuizForm'])->name('quizzes.start.form');

    // Form versturen om quiz echt te starten (POST)
    Route::post('/quizzes/{quiz}/start', [MainController::class, 'startQuiz'])->name('quizzes.start');

    // Volgende vraag (GET)
    Route::get('/quizzes/{quiz}/next', [MainController::class, 'nextQuestion'])->name('quiz.next');

    // Antwoord indienen (POST)
    Route::post('/quizzes/{quiz}/answer', [MainController::class, 'submitAnswer'])->name('quiz.answer');

    // Quiz afronden (GET)
    Route::get('/quizzes/{quiz}/finish', [MainController::class, 'finishQuiz'])->name('quiz.finish');

    Route::get('/mijn-scores/{sessionId}', [App\Http\Controllers\MainController::class, 'studentScores'])
        ->name('student.scores');

    // routes/web.php
    Route::get('/docenten', [App\Http\Controllers\MainController::class, 'index'])->name('teacher.index');
    Route::get('/docenten/{student}', [App\Http\Controllers\MainController::class, 'show'])->name('teacher.show');
    Route::get('/docenten/{student}/quiz/{quiz}', [App\Http\Controllers\MainController::class, 'quizDetail'])->name('teacher.quiz.detail');



    // Quiz stoppen (GET)
    Route::post('/quizzes/{quiz}/stop', [MainController::class, 'stopQuiz'])->name('quiz.stop');

    // ========================
    // Quiz instellingen
    // ========================
    Route::get('/quizzes/{quiz}/settings', [MainController::class, 'quizSettings'])->name('quizzes.settings');

    // ========================
    // Quiz CRUD (Teacher only)
    // ========================
    Route::middleware('auth')->group(function () {
        Route::get('/quizzes/create', [MainController::class, 'createQuiz'])->name('quizzes.create');
        Route::post('/quizzes', [MainController::class, 'storeQuiz'])->name('quizzes.store');
        Route::delete('/quizzes/{quiz}', [MainController::class, 'destroyQuiz'])->name('quizzes.destroy');

        Route::get('/questions/{question}/edit', [MainController::class, 'queEdit'])->name('questions.edit');
        Route::put('/questions/{question}', [MainController::class, 'queUpdate'])->name('questions.update');
        Route::delete('/questions/{question}', [MainController::class, 'quesDestroy'])->name('questions.destroy');
    });


    Route::get('/docenten', [MainController::class, 'docentenPage'])
        ->name('docent.page');

    Route::get('/docenten/{student}', [MainController::class, 'docentStudentScores'])
        ->name('docent.student.scores');

    Route::get('/docenten/{student}/quiz/{quiz}', [MainController::class, 'docentStudentQuizDetail'])
        ->name('docent.student.quiz.detail');

});

require __DIR__.'/auth.php';
