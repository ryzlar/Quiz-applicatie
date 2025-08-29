@extends('layouts.frontend')

@section('content')
    <div class="quiz-finish-container">
        <h2 class="quiz-finish-title">Quiz voltooid: {{ $quiz->title }}</h2>

        <div class="quiz-score-card">
            <p class="quiz-message">Je hebt de quiz voltooid!</p>
            <p class="quiz-score">Score: <strong>{{ $score }}</strong> van <strong>{{ $total }}</strong></p>
            <p class="quiz-percentage">Percentage: <strong>{{ $percentage }}%</strong></p>

            <div class="quiz-progress-bar">
                <div class="quiz-progress" style="width: {{ $percentage }}%"></div>
            </div>
        </div>

        <a href="{{ route('quizzes.index') }}" class="quiz-finish-btn">Terug naar Quizzes</a>
    </div>

@endsection
