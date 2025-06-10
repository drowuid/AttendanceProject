<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
protected $fillable = ['name', 'start_date', 'end_date', 'course_id', 'hours'];

public function attendances()
{
    return $this->hasMany(Attendance::class);
}

public function trainees()
{
    return $this->belongsToMany(\App\Models\Trainee::class);
}


}
