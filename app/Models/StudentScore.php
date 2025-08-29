<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentScore extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'quiz_id', 'score'];

    public function student() {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function quiz() {
        return $this->belongsTo(Quiz::class);
    }

    public function studentAnswers() {
        return $this->hasMany(StudentAnswer::class, 'quiz_id', 'quiz_id')
            ->where('student_id', auth()->id());
    }

}

