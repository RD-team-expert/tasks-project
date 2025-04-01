<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['text' ,'created_by'];

    public function projectQuestionRatings()
    {
        return $this->hasMany(ProjectQuestionRating::class);
    }

    public function taskQuestionRatings()
    {
        return $this->hasMany(TaskQuestionRating::class);
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_question');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
