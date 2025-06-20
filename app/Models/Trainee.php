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
    return $this->hasMany(\App\Models\Absence::class);
}

public function modules()
{
    return $this->belongsToMany(\App\Models\Module::class);
}


}

