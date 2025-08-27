@extends('layouts.frontend')

@section('title', 'Feedback')

@section('content')
    <div class="quiz-container">
        <h2>Feedback voor vraag {{ $currentIndex + 1 }}</h2>
        <p class="question-text">{{ $question->question_text }}</p>

        @if($feedback['isCorrect'])
            <div class="feedback-correct">Correct! 🎉</div>
        @else
            <div class="feedback-wrong">
                Fout. Het juiste antwoord is: <strong>{{ $feedback['correct_answer'] }}</strong>
            </div>
        @endif

        @if($currentIndex + 1 < count(session('quiz_questions')))
            <a href="{{ route('quiz.next', $quiz->id) }}" class="quiz-next">Volgende vraag</a>
        @else
            <a href="{{ route('quiz.finish', $quiz->id) }}" class="quiz-finish">Quiz Voltooien</a>
        @endif
    </div>
@endsection
