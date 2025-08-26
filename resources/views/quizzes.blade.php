@extends('layouts.frontend')

@section('title', 'Quizzes')

@section('content')
    <div class="homepage-container">

        <main class="quiz-page-content">
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
                            <div class="quiz-card">
                                <h3 class="quiz-title">{{ $quiz->title }}</h3>
                                <p class="quiz-info">Aantal vragen: {{ $quiz->questions_count }}</p>
                                <div class="quiz-actions">
                                    <a href="{{ route('quizzes.start', $quiz->id) }}" class="quiz-start-btn">Start</a>

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
                            </div>
                        @endforeach
                    </div>
                @endif
            @endauth
        </main>

    </div>
@endsection
