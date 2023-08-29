<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LecturingSchedule extends Model
{
    const AUDIENCE_FRIDAY = 'friday';
    const AUDIENCE_PUBLIC = 'public';
    const AUDIENCE_MUSLIMAH = 'muslimah';

    protected $fillable = [
        'audience_code', 'date', 'start_time', 'end_time', 'time_text', 'lecturer_name', 'title', 'book_title',
        'book_writer', 'book_link', 'video_link', 'audio_link', 'description', 'creator_id',
    ];

    public function getTimeAttribute()
    {
        $time = $this->start_time.' s/d ';
        $time .= !$this->end_time ? 'selesai' : $this->end_time;

        return $time;
    }

    public function getAudienceAttribute()
    {
        return __('lecturing_schedule.audience_'.$this->audience_code);
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function getDateOnlyAttribute()
    {
        return substr($this->date, -2);
    }

    public function getMonthAttribute()
    {
        return Carbon::parse($this->date)->format('m');
    }

    public function getMonthNameAttribute()
    {
        return Carbon::parse($this->date)->isoFormat('MMM');
    }

    public function getYearAttribute()
    {
        return Carbon::parse($this->date)->format('Y');
    }

    public function getDayNameAttribute(): string
    {
        if (is_null($this->date)) {
            return '';
        }

        $dayName = Carbon::parse($this->date)->isoFormat('dddd');
        if ($dayName == 'Minggu') {
            $dayName = 'Ahad';
        }

        return $dayName;
    }

    public function getFullDateAttribute()
    {
        return Carbon::parse($this->date)->isoFormat('D MMMM Y');
    }
}
