@extends('layouts.frontend')

@section('content')
    <div class="quiz-container">
        <h2>{{ $quiz->title }}</h2>
        <p>Vraag {{ $currentIndex + 1 }} van {{ $quiz->questions->count() }}</p>

        <form action="{{ route('quiz.answer', $quiz->id) }}" method="POST">
            @csrf

            <p class="question-text">{{ $question->question_text }}</p>

            @if($question->type === 'multiple_choice')
                @foreach(json_decode($question->all_answers) as $answer)
                    <div class="option">
                        <label>
                            <input type="radio" name="answer" value="{{ $answer }}" required>
                            {{ $answer }}
                        </label>
                    </div>
                @endforeach
            @elseif($question->type === 'open')
                <input type="text" class="input-text" name="answer" placeholder="Typ hier je antwoord" required>
            @endif

            <input type="hidden" name="question_id" value="{{ $question->id }}">
            <button type="submit" class="submit-btn">Submit</button>
        </form>
    </div>
@endsection
