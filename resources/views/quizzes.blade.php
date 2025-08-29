@extends('layouts.frontend')

@section('title', 'Quizzes')

@section('content')
    <div class="homepage-container">

        <div class="quiz-page-content">
            @auth
                @if(auth()->user()->role === 'teacher')
                    <div class="quiz-add-container">
                        <a href="{{ route('quizzes.create') }}" class="quiz-add-btn">Quiz Toevoegen</a>
                    </div>
                @endif

                @if($quizzes->isEmpty())
                    <p class="no-quizzes-msg">Wacht tot dat een docent een quiz toevoegt</p>
                @else
                    <div class="quiz-list">
                        @foreach($quizzes as $quiz)
                            <div class="quiz-card {{ $quiz->doneBy(auth()->id()) ? 'quiz-completed' : '' }}">
                                <h3 class="quiz-title">{{ $quiz->title }}</h3>
                                <p class="quiz-info">Aantal vragen: {{ $quiz->questions_count }}</p>

                                @if($quiz->doneBy(auth()->id()))
                                    <div class="quiz-overlay">
                                        <span class="quiz-overlay-text">Toets al ingeleverd</span>
                                        <i class="fa fa-lock"></i>
                                    </div>
                                @else
                                    <div class="quiz-actions">
                                        @if(auth()->user()->role === 'student')
                                            <form action="{{ route('quizzes.start', $quiz->id) }}" method="POST" class="student-settings-form">
                                                @csrf
                                                <input type="hidden" name="include_multiple" value="1">
                                                <input type="hidden" name="include_open" value="1">
                                                <button type="submit" class="btn-blue" style="margin-top:1rem;">Starten</button>
                                            </form>
                                        @endif

                                        @if(auth()->user()->role === 'teacher')
                                            <form action="{{ route('quizzes.destroy', $quiz->id) }}" method="POST" class="quiz-delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button class="quiz-delete-btn">Verwijderen</button>
                                            </form>
                                        @endif

                                        <a href="{{ route('quizzes.settings', $quiz->id) }}" class="quiz-settings-btn">
                                            <i class="fa-solid fa-gear"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            @endauth
        </div>

    </div>
@endsection
