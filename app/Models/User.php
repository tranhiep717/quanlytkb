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
        'role',
        'faculty_id',
        'class_cohort',
        'is_locked',
        'code',
        'phone',
        'avatar_url',
        'secondary_faculty_id',
        'gender',
        'dob',
        'id_card',
        'address',
        'degree',
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
            'is_locked' => 'boolean',
        ];
    }

    // Relationships
    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function secondaryFaculty()
    {
        return $this->belongsTo(Faculty::class, 'secondary_faculty_id');
    }

    public function classSections()
    {
        return $this->hasMany(ClassSection::class, 'lecturer_id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    // Lecturer qualifications (courses they can teach)
    public function qualifications()
    {
        return $this->belongsToMany(Course::class, 'lecturer_qualifications', 'lecturer_id', 'course_id')
            ->withPivot('level')
            ->withTimestamps();
    }
}
