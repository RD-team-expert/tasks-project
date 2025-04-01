<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskQuestionRating extends Model
{
    use HasFactory;

    protected $fillable = ['task_rating_id', 'question_id', 'rating','reason'];

    public function taskRating()
    {
        return $this->belongsTo(TaskRating::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
