@extends('layouts.frontend')

@section('content')
    <div class="quiz-play-container">
        <h2>{{ $quiz->title }}</h2>
        <p>Vraag {{ $currentIndex + 1 }} van {{ session('quiz_questions')->count() }}</p>

        <form action="{{ route('quiz.answer', $quiz->id) }}" method="POST" class="quiz-play-form">
            @csrf

            <p class="question-text">{{ $question->question_text }}</p>

            @if($question->type === 'multiple_choice')
                @foreach(json_decode($question->all_answers) as $answer)
                    <label class="quiz-play-option">
                        <input type="radio" name="answer" value="{{ $answer }}" required>
                        {{ $answer }}
                    </label>
                @endforeach
            @elseif($question->type === 'open')
                <input type="text" class="input-text" name="answer" placeholder="Typ hier je antwoord" required>
            @endif

            <input type="hidden" name="question_id" value="{{ $question->id }}">
            <button type="submit" class="submit-btn">Submit</button>
            <button type="button" class="quiz-stop-btn" id="openModalBtn">Stop Quiz</button>
        </form>

        <!-- Modal -->
        <div id="confirmModal" class="quiz-modal hidden">
            <div class="quiz-modal-content">
                <h3 class="modal-title">Weet je zeker dat je de toets wilt beëindigen?</h3>
                <div class="modal-buttons">
                    <button id="cancelBtn" class="modal-cancel-btn">Laat maar</button>
                    <form action="{{ route('quiz.stop', $quiz->id) }}" method="POST" class="modal-form">
                        @csrf
                        <button type="submit" class="modal-confirm-btn">Ga door</button>
                    </form>
                </div>
            </div>
        </div>

        <script>
            const modal = document.getElementById('confirmModal');
            const openBtn = document.getElementById('openModalBtn');
            const cancelBtn = document.getElementById('cancelBtn');

            openBtn.addEventListener('click', () => {
                modal.classList.remove('hidden');
            });

            cancelBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        </script>



    </div>
@endsection
