<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class LecturingSchedule extends Model
{
    const AUDIENCE_PUBLIC = 'public';
    const AUDIENCE_MUSLIMAH = 'muslimah';

    protected $fillable = [
        'audience_code', 'date', 'start_time', 'end_time', 'time_text', 'lecturer', 'title', 'book_title',
        'book_writer', 'book_link', 'video_link', 'audio_link', 'description', 'creator_id',
    ];

    public function getTitleLinkAttribute()
    {
        $title = __('app.show_detail_title', [
            'title' => $this->title, 'type' => __('lecturing_schedule.lecturing_schedule'),
        ]);
        $link = '<a href="'.route('lecturing_schedules.show', $this).'"';
        $link .= ' title="'.$title.'">';
        $link .= $this->title;
        $link .= '</a>';

        return $link;
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }
}
