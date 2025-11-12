<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'dean_id',
        'founding_date',
        'description',
        'is_active'
    ];

    protected $casts = [
        'founding_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function dean()
    {
        return $this->belongsTo(User::class, 'dean_id');
    }
}
