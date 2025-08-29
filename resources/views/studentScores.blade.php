@extends('layouts.frontend')

@section('title', 'Mijn Scores')

@section('content')
    <div class="scores-container">
        <h2>Mijn Scores</h2>

        @forelse($studentScores as $score)
            <div class="score-card">
                <button type="button" class="score-toggle-btn">
                    {{ $score->quiz->title }}
                </button>
                <div class="score-details hidden">
                    <p>Score: {{ $score->score }} van {{ $score->quiz->questions->count() }}
                        ({{ round(($score->score / $score->quiz->questions->count()) * 100) }}%)</p>
                    <p class="{{ $score->score / $score->quiz->questions->count() >= 0.5 ? 'score-passed' : 'score-failed' }}">
                        {{ $score->score / $score->quiz->questions->count() >= 0.5 ? 'Geslaagd' : 'Gefaald' }}
                    </p>

                    <ul class="question-list">
                        @foreach($score->quiz->questions as $question)
                            @php
                                $studentAnswer = $score->studentAnswers->firstWhere('question_id', $question->id);
                                $isCorrect = $studentAnswer && $studentAnswer->is_correct;
                            @endphp
                            <li class="question-item">
                                <p class="question-text">{{ $question->question_text }}</p>
                                <p>
                                    Antwoord:
                                    <span class="{{ $isCorrect ? 'answer-correct' : 'answer-incorrect' }}">
                                    {{ $studentAnswer->answer_text ?? 'Niet beantwoord' }}
                                </span>
                                </p>
                                @if(!$isCorrect)
                                    <p class="correct-answer">Juist antwoord: {{ $question->correct_answer }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @empty
            <p>Je hebt nog geen quizzen gemaakt.</p>
        @endforelse
    </div>

    <script>
        document.querySelectorAll('.score-toggle-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const details = btn.nextElementSibling;
                details.classList.toggle('hidden');
            });
        });
    </script>

@endsection
