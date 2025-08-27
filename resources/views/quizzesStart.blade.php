@extends('layouts.frontend')

@section('title', 'Start Quiz')

@section('content')
    <div class="quiz-container">
        <h2>Vraag {{ $currentIndex + 1 }} van {{ count(session('quiz_questions')) }}</h2>
        <p class="question-text">{{ $question->question_text }}</p>

        <form action="{{ route('quiz.answer', $quiz->id) }}" method="POST" class="quiz-form">
            @csrf

            @if($question->type === 'multiple_choice')
                @foreach(json_decode($question->all_answers) as $answer)
                    <div class="quiz-option">
                        <label>
                            <input type="radio" name="answer" value="{{ $answer }}" required>
                            {{ $answer }}
                        </label>
                    </div>
                @endforeach
            @else
                <input type="text" name="answer" required class="quiz-input" placeholder="Typ je antwoord hier">
            @endif

            <button type="submit" class="quiz-submit">Antwoord indienen</button>
        </form>
    </div>
@endsection
