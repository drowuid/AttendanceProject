<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isTrainer()
    {
        return $this->role === 'trainer';
    }

    public function isTrainee()
    {
        return $this->role === 'trainee';
    }


    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function courseModules()
    {
        return $this->hasManyThrough(Module::class, Course::class, 'id', 'course_id', 'course_id', 'id');
    }

    public function attendances()
    {
        return $this->hasMany(\App\Models\Attendance::class);
    }


public function modules()
{
    return $this->belongsToMany(\App\Models\Module::class);
}

public function absences()
{
    return $this->hasMany(\App\Models\Absence::class);
}


}
