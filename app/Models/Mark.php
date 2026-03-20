<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mark extends Model
{
    protected $fillable = ['exam_id', 'student_id', 'class_id', 'user_subject_id', 'score'];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function userSubject(): BelongsTo
    {
        return $this->belongsTo(UserSubject::class);
    }
}
