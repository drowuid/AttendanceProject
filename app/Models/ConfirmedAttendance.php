<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ConfirmedAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'trainee_id',
        'module_id',
        'date',
        'present',
        'justification_file',
        'justification_reason',
        'confirmed_at',
        'justified_at',
    ];

    protected $casts = [
        'date' => 'date',
        'present' => 'boolean',
        'confirmed_at' => 'datetime',
        'justified_at' => 'datetime',
    ];

    public function trainee()
    {
        return $this->belongsTo(User::class, 'trainee_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function getJustificationFileUrlAttribute()
    {
        if (!$this->justification_file) {
            return null;
        }

        return asset('storage/' . $this->justification_file);
    }

    public function scopePresent($query)
    {
        return $query->where('present', true);
    }

    public function scopeAbsent($query)
    {
        return $query->where('present', false);
    }

    public function scopeJustified($query)
    {
        return $query->where('present', false)
                     ->whereNotNull('justification_file');
    }

    public function scopeUnjustified($query)
    {
        return $query->where('present', false)
                     ->whereNull('justification_file');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('date', Carbon::today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('date', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', Carbon::now()->month)
                     ->whereYear('date', Carbon::now()->year);
    }
}
