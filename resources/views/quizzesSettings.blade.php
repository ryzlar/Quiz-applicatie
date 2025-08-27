@extends('layouts.frontend')

@section('title', 'Quiz Instellingen')

@section('content')
    <div class="homepage-container">
        <main class="page-content">
            <div class="settings-card">
                <h2 class="settings-title">{{ $quiz->title }} - Instellingen</h2>

                @if(auth()->user()->role === 'teacher')
                    <p class="settings-subtitle">
                        Multiple choice vragen: {{ $quiz->questions->where('type','multiple_choice')->count() }},
                        Open vragen: {{ $quiz->questions->where('type','open')->count() }}
                    </p>

                    <table class="settings-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Vraag</th>
                            <th>Type</th>
                            <th>Acties</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($quiz->questions as $index => $question)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $question->question_text }}</td>
                                <td>{{ $question->type === 'multiple_choice' ? 'Multiple Choice' : 'Open' }}</td>
                                <td class="settings-actions">
                                    <a href="{{ route('questions.edit', $question->id) }}" class="btn-blue">Bewerken</a>
                                    <form action="{{ route('questions.destroy', $question->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-red">Verwijderen</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <form action="{{ route('quizzes.start', $quiz->id) }}" method="POST" class="student-settings-form">
                        @csrf
                        <p>Kies welke vragen je wilt gebruiken bij deze quiz:</p>
                        <div class="student-options">
                            <label><input type="checkbox" name="include_multiple" checked> Alleen Multiple Choice</label>
                            <label><input type="checkbox" name="include_open" checked> Alleen Open Vragen</label>
                        </div>
                        <button type="submit" class="btn-blue" style="margin-top:1rem;">Opslaan en Starten</button>
                    </form>
                @endif
            </div>
        </main>
    </div>
@endsection
