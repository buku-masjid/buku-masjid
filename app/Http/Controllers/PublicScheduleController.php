<?php

namespace App\Http\Controllers;

use App\Models\Lecturing;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PublicScheduleController extends Controller
{
    public function today(Request $request)
    {
        $lecturingQuery = Lecturing::query();
        $lecturingQuery->where('date', Carbon::today()->format('Y-m-d'));
        $lecturingQuery->orderBy('date')->orderBy('start_time');
        $lecturings = $lecturingQuery->get()->groupBy('audience_code');
        $audienceCodes = $this->getAudienceCodeList();

        return view('public_schedules.index', compact('lecturings', 'audienceCodes'));
    }

    public function tomorrow(Request $request)
    {
        $lecturingQuery = Lecturing::query();
        $lecturingQuery->where('date', Carbon::tomorrow()->format('Y-m-d'));
        $lecturingQuery->orderBy('date')->orderBy('start_time');
        $lecturings = $lecturingQuery->get()->groupBy('audience_code');
        $audienceCodes = $this->getAudienceCodeList();

        return view('public_schedules.index', compact('lecturings', 'audienceCodes'));
    }

    public function thisWeek(Request $request)
    {
        $lecturingQuery = Lecturing::query();
        $monday = Carbon::now()->startOfWeek()->format('Y-m-d');
        $sunday = Carbon::now()->endOfWeek()->format('Y-m-d');
        $lecturingQuery->whereBetween('date', [$monday, $sunday]);
        $lecturingQuery->orderBy('date')->orderBy('start_time');
        $lecturings = $lecturingQuery->get()->groupBy('audience_code');
        $audienceCodes = $this->getAudienceCodeList();

        return view('public_schedules.index', compact('lecturings', 'audienceCodes'));
    }

    public function nextWeek(Request $request)
    {
        $lecturingQuery = Lecturing::query();
        $monday = Carbon::now()->addWeek()->startOfWeek()->format('Y-m-d');
        $sunday = Carbon::now()->addWeek()->endOfWeek()->format('Y-m-d');
        $lecturingQuery->whereBetween('date', [$monday, $sunday]);
        $lecturingQuery->orderBy('date')->orderBy('start_time');
        $lecturings = $lecturingQuery->get()->groupBy('audience_code');
        $audienceCodes = $this->getAudienceCodeList();

        return view('public_schedules.index', compact('lecturings', 'audienceCodes'));
    }
}
