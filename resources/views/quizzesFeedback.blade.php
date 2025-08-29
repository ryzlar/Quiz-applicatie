@extends('layouts.frontend')

@section('title', 'Feedback')

@section('content')
    <div class="quiz-container">
        <h2>Feedback voor vraag {{ $feedbackIndex + 1 }}</h2>

        @if($feedback['isCorrect'])
            <div class="feedback-correct">Correct! 🎉</div>
        @else
            <div class="feedback-wrong">
                Fout. Het juiste antwoord is: <strong>{{ $feedback['correct_answer'] }}</strong>
            </div>
        @endif

        @if($feedbackIndex + 1 < session('quiz_questions')->count())
            <a href="{{ route('quiz.next', $quiz->id) }}" class="quiz-next">Volgende vraag</a>
        @else
            <a href="{{ route('quiz.finish', $quiz->id) }}" class="quiz-finish">Quiz Voltooien</a>
        @endif


    </div>
@endsection
