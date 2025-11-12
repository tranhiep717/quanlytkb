<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'building',
        'floor',
        'capacity',
        'equipment',
        'status'
    ];

    protected $casts = [
        'equipment' => 'array', // JSON field
    ];

    /**
     * Relationship: Room can have many class sections
     */
    public function classSections()
    {
        return $this->hasMany(ClassSection::class, 'room_id');
    }

    /**
     * Get formatted equipment string
     */
    public function getEquipmentStringAttribute()
    {
        if (!$this->equipment || !is_array($this->equipment)) {
            return 'Không có';
        }
        return implode(', ', $this->equipment);
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return $this->status === 'active' ? 'success' : 'secondary';
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return $this->status === 'active' ? 'Hoạt động' : 'Tạm ngưng';
    }
}
