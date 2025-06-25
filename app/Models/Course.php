<?php

// app/Models/Course.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['name', 'title', 'total_hours'];

    
    public function modules()
{
    return $this->hasMany(Module::class, 'course_id');
}

    

}

