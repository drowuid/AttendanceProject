<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    public function attendances()
{
    return $this->hasMany(Attendance::class);
}

}
