<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LecturingSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PublicScheduleController extends Controller
{
    public function today(Request $request)
    {
        $lecturingScheduleQuery = LecturingSchedule::query();
        $lecturingScheduleQuery->where('date', Carbon::today()->format('Y-m-d'));
        $lecturingScheduleQuery->orderBy('date')->orderBy('start_time');
        $lecturingSchedules = $lecturingScheduleQuery->get()->groupBy('audience_code');

        return view('public_schedules.index', compact('lecturingSchedules'));
    }

    public function tomorrow(Request $request)
    {
        $lecturingScheduleQuery = LecturingSchedule::query();
        $lecturingScheduleQuery->where('date', Carbon::tomorrow()->format('Y-m-d'));
        $lecturingScheduleQuery->orderBy('date')->orderBy('start_time');
        $lecturingSchedules = $lecturingScheduleQuery->get()->groupBy('audience_code');

        return view('public_schedules.index', compact('lecturingSchedules'));
    }
}
