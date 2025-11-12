<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'class_section_id'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function classSection()
    {
        return $this->belongsTo(ClassSection::class);
    }
}
