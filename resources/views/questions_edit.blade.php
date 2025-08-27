@extends('layouts.frontend')

@section('title', 'Vraag Bewerken')

@section('content')
    <div class="homepage-container">
        <main class="page-content">
            <div class="edit-question-card">
                <h2>Bewerk Vraag</h2>

                <form action="{{ route('questions.update', $question->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div>
                        <label>Vraag Tekst</label>
                        <input type="text" name="question_text" value="{{ old('question_text', $question->question_text) }}">
                        @error('question_text')
                        <p>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label>Type</label>
                        <select name="type">
                            <option value="multiple_choice" {{ $question->type === 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                            <option value="open" {{ $question->type === 'open' ? 'selected' : '' }}>Open</option>
                        </select>
                        @error('type')
                        <p>{{ $message }}</p>
                        @enderror
                    </div>

                    @if($question->type === 'multiple_choice')
                        @php $options = json_decode($question->options, true) @endphp
                        @foreach($options ?? [] as $i => $option)
                            <div>
                                <label>Optie {{ $i + 1 }}</label>
                                <input type="text" name="options[{{ $i }}][text]" value="{{ $option['text'] }}">
                                <label>Correct?</label>
                                <input type="checkbox" name="options[{{ $i }}][is_correct]" {{ $option['is_correct'] ? 'checked' : '' }}>
                            </div>
                        @endforeach
                    @endif

                    <div>
                        <label>Antwoord (alleen voor open vragen)</label>
                        <input type="text" name="answer" value="{{ old('answer', $question->answer) }}">
                    </div>

                    <button type="submit" class="btn-blue">Opslaan</button>
                </form>
            </div>
        </main>
    </div>
@endsection
