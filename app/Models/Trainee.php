<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainee extends Model
{
    //

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }



public function absences()
{
    return $this->hasMany(\App\Models\Absence::class, 'user_id', 'user_id');
}

public function modules()
{
    return $this->belongsToMany(\App\Models\Module::class, 'module_trainee');
    // or whatever pivot table you have
}


}

