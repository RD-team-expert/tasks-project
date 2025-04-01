<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'name', 'description', 'start_date', 'end_date', 'status', 'created_by','assigned_to'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function ratings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TaskRating::class);
    }
    public function assignedEmployee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'task_question');
    }

}
