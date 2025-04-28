<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'start_date', 'end_date', 'created_by','assigned_to','manager_id', 'status', 'notes'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function ratings()
    {
        return $this->hasMany(ProjectRating::class);
    }

    public function assignedEmployee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    public function employees()
    {
        return $this->belongsToMany(User::class);
    }

    public function questions()
    {
        return $this->hasMany(ProjectQuestion::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

}
