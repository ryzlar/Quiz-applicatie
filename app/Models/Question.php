<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['quiz_id', 'question_text', 'type', 'correct_answer'];

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

