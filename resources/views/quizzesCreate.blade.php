@extends('layouts.frontend')

@section('title', 'Quiz Toevoegen')

@section('content')
    <div class="homepage-container">

        <main class="page-content">
            <div class="create-quiz-card">
                <h2 class="create-quiz-title">Nieuwe Quiz Toevoegen</h2>

                <form action="{{ route('quizzes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="create-quiz-group">
                        <label for="title" class="create-quiz-label">Quiz Titel</label>
                        <input type="text" name="title" id="title" class="create-quiz-input" placeholder="Bijv. Wiskunde basis">
                        @error('title')
                        <p class="create-quiz-error">{{ $message }}</p>
                        @enderror
                    </div>


                    <div class="create-quiz-group">
                        <label for="questions_file" class="create-quiz-label">Upload JSON of CSV</label>
                        <input type="file" name="questions_file" id="questions_file" class="create-quiz-file" accept=".json,.csv">
                        @error('questions_file')
                        <p class="create-quiz-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="create-quiz-group" style="text-align:center; margin-top:2rem;">
                        <button type="submit" class="create-quiz-submit">Quiz Maken</button>
                    </div>
                </form>
            </div>
        </main>

    </div>
@endsection
