<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class LecturingSchedule extends Model
{
    const AUDIENCE_FRIDAY = 'friday';
    const AUDIENCE_PUBLIC = 'public';
    const AUDIENCE_MUSLIMAH = 'muslimah';

    protected $fillable = [
        'audience_code', 'date', 'start_time', 'end_time', 'time_text', 'lecturer', 'title', 'book_title',
        'book_writer', 'book_link', 'video_link', 'audio_link', 'description', 'creator_id',
    ];

    public function getTimeAttribute()
    {
        $time = !$this->time_text ? '' : $this->time_text.', ';
        $time .= $this->start_time.' s/d ';
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
}
