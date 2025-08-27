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


    public function quizSettings(Quiz $quiz) {
        // Tel het aantal multiple-choice en open vragen

        $multipleCount = $quiz->questions()->where('type', 'multiple_choice')->count();
        $openCount = $quiz->questions()->where('type', 'open')->count();

        // Check of de gebruiker docent of student is
        $isTeacher = auth()->user()->role === 'teacher'; // zorg dat je rol in users tabel staat

        return view('quizzesSettings', compact('quiz', 'multipleCount', 'openCount', 'isTeacher'));
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
            $question_text = $question['question_text'] ?? null;
            $type_raw = $question['type'] ?? null;

            if (!$question_text || !$type_raw) {
                throw new \Exception("Vraag op index $index mist 'question_text' of 'type'.");
            }

            $type = strtolower(trim($type_raw));

            if ($type === 'multiple_choice') {
                if (!isset($question['options']) || !is_array($question['options']) || count($question['options']) < 2) {
                    throw new \Exception("Multiple choice vraag op index $index heeft geen geldige opties.");
                }

                $all_answers = [];
                $correct_answer = null;

                foreach ($question['options'] as $opt) {
                    $text = $opt['text'] ?? null;
                    if (!$text) continue;
                    $all_answers[] = $text;
                    if (!empty($opt['is_correct'])) {
                        $correct_answer = $text;
                    }
                }

                if (!$correct_answer) {
                    throw new \Exception("Multiple choice vraag op index $index mist een correct antwoord.");
                }

                Question::create([
                    'quiz_id' => $quiz->id,
                    'question_text' => $question_text,
                    'type' => 'multiple_choice',
                    'all_answers' => json_encode($all_answers),
                    'correct_answer' => $correct_answer,
                ]);

            } elseif ($type === 'open') {
                $answer = $question['answer'] ?? null;
                if (!$answer) {
                    throw new \Exception("Open vraag op index $index mist een antwoord.");
                }

                Question::create([
                    'quiz_id' => $quiz->id,
                    'question_text' => $question_text,
                    'type' => 'open',
                    'all_answers' => null,
                    'correct_answer' => $answer,
                ]);

            } else {
                throw new \Exception("Onbekend vraagtype '$type' op index $index.");
            }
        }
    }

    protected function importCsv($file, Quiz $quiz)
    {
        $rows = array_map('str_getcsv', file($file));
        $header = array_map('trim', array_shift($rows)); // eerste regel = header
        $headerCount = count($header);

        foreach ($rows as $index => $row) {
            // Zorg dat rij evenveel elementen heeft als header
            $row = array_pad($row, $headerCount, null);
            $rowData = array_combine($header, $row);

            $question_text = $rowData['question_text'] ?? null;
            $type_raw = $rowData['question_type'] ?? null;

            if (!$question_text || !$type_raw) {
                throw new \Exception("CSV regel ".($index+2)." mist question_text of question_type.");
            }

            $type = strtolower(trim($type_raw));

            if ($type === 'multiple_choice') {
                $existingQuestion = Question::firstOrCreate(
                    [
                        'quiz_id' => $quiz->id,
                        'question_text' => $question_text,
                        'type' => 'multiple_choice'
                    ],
                    [
                        'all_answers' => json_encode([]),
                        'correct_answer' => null
                    ]
                );

                $all_answers = json_decode($existingQuestion->all_answers, true) ?? [];
                $option_text = $rowData['option_text'] ?? null;
                if ($option_text && !in_array($option_text, $all_answers)) {
                    $all_answers[] = $option_text;
                }

                $existingQuestion->all_answers = json_encode($all_answers);

                if (!empty($rowData['is_correct']) && $rowData['is_correct'] == 1) {
                    $existingQuestion->correct_answer = $option_text;
                }

                $existingQuestion->save();

            } elseif ($type === 'open') {
                $answer = $rowData['answer'] ?? null;
                if (!$answer) {
                    throw new \Exception("CSV regel ".($index+2)." mist een antwoord voor open vraag.");
                }

                Question::create([
                    'quiz_id' => $quiz->id,
                    'question_text' => $question_text,
                    'type' => 'open',
                    'all_answers' => null,
                    'correct_answer' => $answer,
                ]);

            } else {
                throw new \Exception("CSV regel ".($index+2)." heeft onbekend type '$type'.");
            }
        }
    }





    public function destroyQuiz(Quiz $quiz) {
        $quiz->delete();
        return redirect()->route('quizzes.index')->with('success', 'Quiz verwijderd!');
    }

    public function queEdit(Question $question)
    {
        // Optioneel: check of de ingelogde docent eigenaar is van de quiz
        if(auth()->user()->role === 'teacher' && $question->quiz->teacher_id !== auth()->id()) {
            abort(403, 'Je mag deze vraag niet bewerken.');
        }

        return view('questions_edit', compact('question'));
    }


    // Update de vraag in de database
    public function queUpdate(Request $request, Question $question)
    {
        $request->validate([
            'question_text' => 'required|string',
            'type' => 'required|in:multiple_choice,open',
            'all_answers' => 'nullable|array',
            'correct_answer' => 'nullable|string',
        ]);

        $question->question_text = $request->question_text;
        $question->type = $request->type;

        if ($request->type === 'multiple_choice') {
            $question->all_answers = $request->all_answers ?? [];
            $question->correct_answer = $request->correct_answer;
        } else {
            $question->all_answers = null;
            $question->correct_answer = $request->correct_answer;
        }

        $question->save();

        return redirect()->route('quizzes.settings', $question->quiz_id)
            ->with('success', 'Vraag succesvol bijgewerkt!');
    }


    // Verwijder een vraag
    public function quesDestroy(Question $question)
    {
        $quizId = $question->quiz_id;
        $question->delete();

        return redirect()->route('quizzes.settings', $quizId)
            ->with('success', 'Vraag succesvol verwijderd!');
    }

    public function startQuizForm(Quiz $quiz) {
        return view('quizzesStart', compact('quiz'));
    }

    public function startQuiz(Request $request, Quiz $quiz) {
        $request->validate([
            'question_type' => 'required|in:multiple_choice,open,both',
        ]);

        $type = $request->question_type;

        if($type === 'both') {
            $questions = $quiz->questions()->get();
        } else {
            $questions = $quiz->questions()->where('type', $type)->get();
        }

        // Sla de geselecteerde vragen tijdelijk op in session
        $request->session()->put('quiz_questions', $questions);
        $request->session()->put('current_question', 0);
        $request->session()->put('score', 0);

        return redirect()->route('quiz.next', $quiz->id);
    }

    public function nextQuestion(Quiz $quiz, Request $request) {
        $questions = $request->session()->get('quiz_questions', collect());
        $currentIndex = $request->session()->get('current_question', 0);

        if($currentIndex >= $questions->count()) {
            return redirect()->route('quiz.finish', $quiz->id);
        }

        $question = $questions[$currentIndex];

        return view('quizzes.question', compact('quiz', 'question', 'currentIndex'));
    }

    public function submitAnswer(Quiz $quiz, Request $request) {
        $questions = $request->session()->get('quiz_questions', collect());
        $currentIndex = $request->session()->get('current_question', 0);

        if($currentIndex >= $questions->count()) {
            return redirect()->route('quiz.finish', $quiz->id);
        }

        $question = $questions[$currentIndex];

        $isCorrect = false;

        if($question->type === 'multiple_choice') {
            $isCorrect = $request->answer == $question->correct_answer;
        } else {
            $isCorrect = strtolower(trim($request->answer)) === strtolower(trim($question->correct_answer));
        }

        // update score
        if($isCorrect) {
            $request->session()->increment('score');
        }

        // feedback tonen
        $feedback = [
            'isCorrect' => $isCorrect,
            'correct_answer' => $question->correct_answer
        ];

        $request->session()->put('current_question', $currentIndex + 1);

        return view('quizzesFeedback', compact('quiz', 'question', 'feedback'));
    }

    public function finishQuiz(Quiz $quiz, Request $request) {
        $score = $request->session()->get('score', 0);
        $total = $request->session()->get('quiz_questions', collect())->count();

        // hier kan je opslaan in database: scores, user_id, quiz_id
        // bv QuizResult::create([...]);

        // session opruimen
        $request->session()->forget(['quiz_questions', 'current_question', 'score']);

        return view('quizzes.finish', compact('quiz', 'score', 'total'));
    }


}
