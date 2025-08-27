@extends('layouts.frontend')

@section('content')
    <div class="quiz-container">
        <h2>Quiz voltooid: {{ $quiz->title }}</h2>

        <div class="score-box">
            <p>Je hebt de quiz voltooid!</p>
            <p>Score: <strong>{{ $score }}</strong> van <strong>{{ $total }}</strong></p>
            @php
                $percentage = $total > 0 ? round(($score / $total) * 100) : 0;
            @endphp
            <p>Percentage: <strong>{{ $percentage }}%</strong></p>
        </div>

        <a href="{{ route('quizzes.index') }}" class="finish-btn">Terug naar Quizzes</a>
    </div>
@endsection
