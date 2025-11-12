<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'day_of_week',
        'start_period',
        'end_period',
        'start_time',
        'end_time',
        'status'
    ];

    // Relationships
    public function classSections()
    {
        return $this->hasMany(ClassSection::class, 'shift_id');
    }

    // Accessors
    public function getDayNameAttribute(): string
    {
        $days = [1 => 'Thứ 2', 2 => 'Thứ 3', 3 => 'Thứ 4', 4 => 'Thứ 5', 5 => 'Thứ 6', 6 => 'Thứ 7', 7 => 'CN'];
        return $days[$this->day_of_week] ?? 'N/A';
    }

    public function getTimeRangeAttribute(): string
    {
        // Prefer explicit times if present
        if ($this->start_time && $this->end_time) {
            return substr($this->start_time, 0, 5) . ' - ' . substr($this->end_time, 0, 5);
        }
        // Compute from periods (starting at 07:00, 50 minutes per period)
        $start = $this->periodToTime($this->start_period, true);
        $end = $this->periodToTime($this->end_period, false);
        return $start . ' - ' . $end;
    }

    public function getFrameAttribute(): string
    {
        // Derive frame (Sáng/Chiều/Tối) from start_period
        if ($this->start_period >= 1 && $this->start_period <= 3) return 'Sáng';
        if ($this->start_period >= 4 && $this->start_period <= 6) return 'Chiều';
        return 'Tối';
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status === 'active' ? 'Hoạt động' : 'Tạm ngưng';
    }

    public function getStatusColorAttribute(): string
    {
        return $this->status === 'active' ? 'success' : 'secondary';
    }

    // Helpers
    private function periodToTime(int $period, bool $isStart): string
    {
        // Base 07:00. Period n starts at 07:00 + (n-1)*50 and ends at 07:00 + n*50
        $minutes = 7 * 60 + ($isStart ? ($period - 1) * 50 : ($period) * 50);
        $h = floor($minutes / 60);
        $m = $minutes % 60;
        return sprintf('%02d:%02d', $h, $m);
    }
}
