@extends('layouts.frontend')

@section('title', 'Home')

@section('content')
    <div class="homepage-container">
        <section class="hero">
            <h1>Welkom bij <span class="logo-quiz">Quiz</span><span class="logo-lab">Lab</span>!</h1>
            <p>Test je kennis en leer op een leuke manier. 🎉</p>
        </section>

        <section class="features">
            <div class="feature-card">
                <i class="fa-solid fa-file-import fa-2x"></i>
                <h3>Upload Vragen</h3>
                <p>Docenten kunnen eenvoudig JSON/CSV bestanden uploaden voor nieuwe vragen.</p>
            </div>
            <div class="feature-card">
                <i class="fa-solid fa-pen fa-2x"></i>
                <h3>Maak Quizzes</h3>
                <p>Studenten kunnen multiple-choice en open vragen beantwoorden.</p>
            </div>
            <div class="feature-card">
                <i class="fa-solid fa-chart-line fa-2x"></i>
                <h3>Bekijk Resultaten</h3>
                <p>Direct feedback en scores voor studenten, overzichtelijk voor docenten.</p>
            </div>
        </section>
    </div>
@endsection
