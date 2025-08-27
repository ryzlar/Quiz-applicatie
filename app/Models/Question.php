<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question_text',
        'type', // bijv. 'multiple_choice' of 'open'
        'all_answers',   // alle opties (json)
        'correct_answer' // enkel het juiste antwoord (of leeg bij open)
    ];

    protected $casts = [
        'all_answers' => 'array', // zodat je automatisch met array kan werken ipv string
    ];

    public function quiz() {
        return $this->belongsTo(Quiz::class);
    }

    public function options() {
        return $this->hasMany(Option::class);
    }

    public function studentAnswers() {
        return $this->hasMany(StudentAnswer::class);
    }
}

