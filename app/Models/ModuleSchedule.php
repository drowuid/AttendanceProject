<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'scheduled_date',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
