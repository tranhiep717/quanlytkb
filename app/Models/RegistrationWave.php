<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationWave extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year',
        'term',
        'name',
        'audience',
        'starts_at',
        'ends_at'
    ];

    protected $casts = [
        'audience' => 'array',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function classSections()
    {
        return $this->belongsToMany(ClassSection::class, 'registration_wave_class_section');
    }
}
