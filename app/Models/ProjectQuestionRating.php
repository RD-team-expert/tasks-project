<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectQuestionRating extends Model
{
    use HasFactory;

    protected $fillable = ['project_rating_id', 'question_id', 'rating'];

    public function projectRating()
    {
        return $this->belongsTo(ProjectRating::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
