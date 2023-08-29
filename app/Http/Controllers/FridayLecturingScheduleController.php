<?php

namespace App\Http\Controllers;

use App\Models\LecturingSchedule;
use Illuminate\Http\Request;

class FridayLecturingScheduleController extends Controller
{
    public function create()
    {
        $this->authorize('create', new LecturingSchedule);

        return view('friday_lecturing_schedules.create');
    }

    public function store(Request $request)
    {
        $newLecturingSchedule = $request->validate([
            'date' => ['required', 'date_format:Y-m-d'],
            'start_time' => ['required', 'date_format:H:i'],
            'lecturer_name' => ['required', 'max:60'],
            'title' => ['nullable', 'max:60'],
            'video_link' => ['nullable', 'max:255'],
            'audio_link' => ['nullable', 'max:255'],
            'description' => ['nullable', 'max:255'],
        ]);
        $newLecturingSchedule['creator_id'] = auth()->id();
        $newLecturingSchedule['audience_code'] = 'friday';

        $lecturingSchedule = LecturingSchedule::create($newLecturingSchedule);
        flash(__('lecturing_schedule.created'), 'success');

        return redirect()->route('friday_lecturing_schedules.show', $lecturingSchedule);
    }

    public function show(LecturingSchedule $lecturingSchedule)
    {
        if (!in_array($lecturingSchedule->audience_code, [LecturingSchedule::AUDIENCE_FRIDAY])) {
            return redirect()->route('lecturing_schedules.show', $lecturingSchedule);
        }

        return view('friday_lecturing_schedules.show', compact('lecturingSchedule'));
    }

    public function edit(LecturingSchedule $lecturingSchedule)
    {
        $this->authorize('update', $lecturingSchedule);

        return view('friday_lecturing_schedules.edit', compact('lecturingSchedule'));
    }

    public function update(Request $request, LecturingSchedule $lecturingSchedule)
    {
        $lecturingScheduleData = $request->validate([
            'date' => ['required', 'date_format:Y-m-d'],
            'start_time' => ['required', 'date_format:H:i'],
            'lecturer_name' => ['required', 'max:60'],
            'title' => ['nullable', 'max:60'],
            'video_link' => ['nullable', 'max:255'],
            'audio_link' => ['nullable', 'max:255'],
            'description' => ['nullable', 'max:255'],
        ]);
        $lecturingSchedule->update($lecturingScheduleData);
        flash(__('lecturing_schedule.updated'), 'success');

        return redirect()->route('friday_lecturing_schedules.show', $lecturingSchedule);
    }
}
