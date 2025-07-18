<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfirmedAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'trainee_id',
        'module_id',
        'date',
        'present',
        'justification_file',
    ];

    public function trainee()
    {
        return $this->belongsTo(User::class, 'trainee_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}

