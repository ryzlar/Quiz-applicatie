@extends('layouts.frontend')

@section('title', 'Docent Page')

@section('content')
    <div class="docent-page-container">
        <h1>Overzicht Studenten</h1>

        <ul class="student-list">
            @foreach($students as $student)
                <li class="student-item">
                    <button class="toggle-btn" onclick="toggleElement('student-{{ $student->id }}')">
                        {{ $student->name }}
                    </button>

                    <div id="student-{{ $student->id }}" class="hidden">
                        <ul class="quiz-list">
                            @foreach($student->studentScores as $score)
                                <li class="quiz-item">
                                    <button class="toggle-btn" onclick="toggleElement('quiz-{{ $student->id }}-{{ $score->quiz->id }}')">
                                        {{ $score->quiz->title }} - Score: {{ $score->score }}/{{ $score->quiz->questions->count() }}
                                    </button>

                                    <div id="quiz-{{ $student->id }}-{{ $score->quiz->id }}" class="hidden">
                                        <p>
                                            Percentage:
                                            <strong class="{{ ($score->score / max(1,$score->quiz->questions->count()))*100 >= 50 ? 'answer-correct' : 'answer-wrong' }}">
                                                {{ round(($score->score / max(1,$score->quiz->questions->count()))*100) }}%
                                            </strong>
                                        </p>

                                        {{-- Progress bar --}}
                                        <div class="quiz-progress-bar">
                                            <div class="quiz-progress" style="width: {{ ($score->score / max(1,$score->quiz->questions->count()))*100 }}%"></div>
                                        </div>

                                        <ul class="question-list">
                                            @foreach($score->quiz->questions as $question)
                                                @php
                                                    $answer = $student->studentAnswers->firstWhere('question_id', $question->id);
                                                @endphp
                                                <li class="question-item">
                                                    <p><strong>{{ $question->question_text }}</strong></p>

                                                    <p>
                                                        Antwoord:
                                                        @if($answer)
                                                            <span class="{{ $answer->is_correct ? 'answer-correct' : 'answer-wrong' }}">
                                                                {{ $answer->answer_text ?? 'Niet beantwoord' }}
                                                            </span>
                                                        @else
                                                            <span class="answer-wrong">Niet beantwoord</span>
                                                        @endif
                                                    </p>

                                                    @if(!$answer || !$answer->is_correct)
                                                        <p class="answer-correct">
                                                            Juist antwoord: {{ $question->correct_answer }}
                                                        </p>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <script>
        function toggleElement(id) {
            const el = document.getElementById(id);
            el.classList.toggle('hidden');
        }
    </script>
@endsection
