<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'question_id', 'quiz_id', 'answer_text', 'is_correct'];

    public function student() {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function question() {
        return $this->belongsTo(Question::class);
    }


    public function quiz() {
        return $this->belongsTo(Quiz::class);
    }
}
