<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LecturingSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'creator_id'];

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
