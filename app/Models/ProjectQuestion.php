<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'question', 'rating', 'rated_by'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function rater()
    {
        return $this->belongsTo(User::class, 'rated_by');
    }
}
