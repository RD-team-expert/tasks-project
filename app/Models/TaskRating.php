<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskRating extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'review' ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function questionRatings()
    {
        return $this->hasMany(TaskQuestionRating::class);
    }
}
