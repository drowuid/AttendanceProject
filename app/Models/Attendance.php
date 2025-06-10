<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    public function trainee()
{
    return $this->belongsTo(User::class, 'trainee_id');
}

public function module()
{
    return $this->belongsTo(Module::class);
}

}
