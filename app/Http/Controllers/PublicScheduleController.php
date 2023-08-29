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
        $audienceCodes = $this->getAudienceCodeList();

        return view('public_schedules.index', compact('lecturingSchedules', 'audienceCodes'));
    }

    public function tomorrow(Request $request)
    {
        $lecturingScheduleQuery = LecturingSchedule::query();
        $lecturingScheduleQuery->where('date', Carbon::tomorrow()->format('Y-m-d'));
        $lecturingScheduleQuery->orderBy('date')->orderBy('start_time');
        $lecturingSchedules = $lecturingScheduleQuery->get()->groupBy('audience_code');
        $audienceCodes = $this->getAudienceCodeList();

        return view('public_schedules.index', compact('lecturingSchedules', 'audienceCodes'));
    }

    public function thisWeek(Request $request)
    {
        $lecturingScheduleQuery = LecturingSchedule::query();
        $monday = Carbon::now()->startOfWeek()->format('Y-m-d');
        $sunday = Carbon::now()->endOfWeek()->format('Y-m-d');
        $lecturingScheduleQuery->whereBetween('date', [$monday, $sunday]);
        $lecturingScheduleQuery->orderBy('date')->orderBy('start_time');
        $lecturingSchedules = $lecturingScheduleQuery->get()->groupBy('audience_code');
        $audienceCodes = $this->getAudienceCodeList();

        return view('public_schedules.index', compact('lecturingSchedules', 'audienceCodes'));
    }

    public function nextWeek(Request $request)
    {
        $lecturingScheduleQuery = LecturingSchedule::query();
        $monday = Carbon::now()->addWeek()->startOfWeek()->format('Y-m-d');
        $sunday = Carbon::now()->addWeek()->endOfWeek()->format('Y-m-d');
        $lecturingScheduleQuery->whereBetween('date', [$monday, $sunday]);
        $lecturingScheduleQuery->orderBy('date')->orderBy('start_time');
        $lecturingSchedules = $lecturingScheduleQuery->get()->groupBy('audience_code');
        $audienceCodes = $this->getAudienceCodeList();

        return view('public_schedules.index', compact('lecturingSchedules', 'audienceCodes'));
    }
}
