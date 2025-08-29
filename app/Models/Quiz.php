<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'teacher_id'];

    public function teacher() {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function studentScores() {
        return $this->hasMany(StudentScore::class);
    }


    /**
     * Check of een student deze quiz al voltooid heeft
     */
    public function doneBy($studentId)
    {
        return $this->studentScores()->where('student_id', $studentId)->exists();
    }
}

