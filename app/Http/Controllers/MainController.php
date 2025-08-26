<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        return view('homepage');
    }

    public function quizzes() {
        // eager load questions count voor performance
        $quizzes = Quiz::withCount('questions')->get();
        return view('quizzes', compact('quizzes'));
    }

    public function startQuiz(Quiz $quiz) {
        // logica om quiz te starten (bijv. vragen ophalen)
        $questions = $quiz->questions;
        return view('quizzes.start', compact('quiz', 'questions'));
    }

    public function quizSettings(Quiz $quiz) {
        // optionele instellingenpagina
        return view('quizzes.settings', compact('quiz'));
    }

    public function createQuiz() {
        return view('quizzesCreate');
    }

    public function storeQuiz(Request $request)

    {

        $request->validate([
            'title' => 'required|string|max:255',
            'questions_file' => 'required|file|mimes:csv,json',
        ], [
            'questions_file.mimes' => 'Het bestand moet een JSON of CSV zijn.',
        ]);

        $file = $request->file('questions_file');
        $extension = $file->getClientOriginalExtension();

        try {
            // Maak de quiz eerst aan
            $quiz = Quiz::create([
                'title' => $request->title,
                'teacher_id' => auth()->id(),
            ]);

            if ($extension === 'json') {
                $this->importJson($file, $quiz);
            } elseif ($extension === 'csv') {
                $this->importCsv($file, $quiz);
            } else {
                return back()->withErrors(['questions_file' => 'Ongeldig bestandstype.'])->withInput();
            }

        } catch (\Exception $e) {
            return back()->withErrors(['questions_file' => 'Fout bij het verwerken van het bestand: ' . $e->getMessage()])->withInput();
        }

        return redirect()->route('quizzes.index')->with('success', 'Quiz succesvol aangemaakt!');
    }

    protected function importJson($file, Quiz $quiz)
    {
        $data = json_decode(file_get_contents($file), true);

        if (!isset($data['questions']) || !is_array($data['questions'])) {
            throw new \Exception('JSON bevat geen geldige questions array.');
        }

        foreach ($data['questions'] as $index => $question) {
            if (empty($question['question_text']) || empty($question['type'])) {
                throw new \Exception("JSON vraag op index $index is ongeldig.");
            }

            if ($question['type'] === 'multiple_choice' && (!isset($question['options']) || !is_array($question['options']))) {
                throw new \Exception("Multiple choice vraag op index $index heeft geen geldige opties.");
            }

            Question::create([
                'quiz_id' => $quiz->id,
                'question_text' => $question['question_text'],
                'type' => $question['type'],
                'options' => isset($question['options']) ? json_encode($question['options']) : null,
                'answer' => $question['answer'] ?? null,
            ]);
        }
    }


    protected function importCsv($file, Quiz $quiz)
    {
        $rows = array_map('str_getcsv', file($file));
        $header = array_shift($rows);

        if (!$header || !in_array('question_text', $header) || !in_array('type', $header)) {
            throw new \Exception('CSV bevat ongeldige kolommen.');
        }

        $questions = [];

        foreach ($rows as $index => $row) {
            $rowData = @array_combine($header, $row);
            if (!$rowData || empty($rowData['question_text']) || empty($rowData['type'])) {
                throw new \Exception("CSV regel ".($index+2)." bevat ongeldige data.");
            }

            if ($rowData['type'] === 'multiple_choice') {
                $key = $rowData['question_text'];
                $questions[$key]['quiz_id'] = $quiz->id;
                $questions[$key]['question_text'] = $rowData['question_text'];
                $questions[$key]['type'] = $rowData['type'];
                $questions[$key]['options'][] = [
                    'text' => $rowData['option_text'] ?? '',
                    'is_correct' => $rowData['is_correct'] == 1 ? true : false
                ];
            } else { // open vragen
                if (empty($rowData['answer'])) {
                    throw new \Exception("CSV regel ".($index+2)." heeft geen antwoord voor open vraag.");
                }
                Question::create([
                    'quiz_id' => $quiz->id,
                    'question_text' => $rowData['question_text'],
                    'type' => $rowData['type'],
                    'options' => null,
                    'answer' => $rowData['answer'],
                ]);
            }
        }

        foreach ($questions as $q) {
            Question::create([
                'quiz_id' => $q['quiz_id'],
                'question_text' => $q['question_text'],
                'type' => $q['type'],
                'options' => json_encode($q['options']),
                'answer' => null,
            ]);
        }
    }



    public function destroyQuiz(Quiz $quiz) {
        $quiz->delete();
        return redirect()->route('quizzes.index')->with('success', 'Quiz verwijderd!');
    }
}
