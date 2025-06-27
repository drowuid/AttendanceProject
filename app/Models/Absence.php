<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Module;

class Absence extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = ['user_id', 'module_id', 'date', 'reason'];

    protected $dates = ['deleted_at'];


    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

public function module()
{
    return $this->belongsTo(Module::class, 'module_id');
}

public function trainee()
{
    return $this->belongsTo(User::class, 'user_id');
}

public function attendance()
{
    return $this->belongsTo(Attendance::class);
}

}
