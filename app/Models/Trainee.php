<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainee extends Model
{
    // If your table is not 'trainees', specify it:
    // protected $table = 'trainees';

    // Relationships

    public function user()
    {
        // Assuming 'user_id' is the foreign key in the trainees table
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function modules()
    {
        // Many-to-many relationship with Module
        return $this->belongsToMany(\App\Models\Module::class, 'module_trainee');
        // If your pivot table is named differently, update the second argument
    }

    public function absences()
    {
        // One-to-many relationship with Absence
        // If 'user_id' is the foreign key in absences table and matches this trainee's user_id
        return $this->hasMany(\App\Models\Absence::class, 'user_id', 'user_id');
    }

    public function attendances()
    {
        // One-to-many relationship with Attendance
        return $this->hasMany(\App\Models\Attendance::class, 'trainee_id', 'id');
        // Adjust foreign key if needed
    }
}

