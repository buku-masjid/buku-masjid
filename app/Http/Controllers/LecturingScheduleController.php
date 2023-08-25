<?php

namespace App\Http\Controllers;

use App\Models\LecturingSchedule;
use Illuminate\Http\Request;

class LecturingScheduleController extends Controller
{
    public function index(Request $request)
    {
        $lecturingScheduleQuery = LecturingSchedule::query();
        $lecturingScheduleQuery->where('title', 'like', '%'.$request->get('q').'%');
        $lecturingScheduleQuery->orderBy('title');
        $lecturingSchedules = $lecturingScheduleQuery->paginate(25);

        return view('lecturing_schedules.index', compact('lecturingSchedules'));
    }

    public function create()
    {
        $this->authorize('create', new LecturingSchedule);

        return view('lecturing_schedules.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', new LecturingSchedule);

        $newLecturingSchedule = $request->validate([
            'title'       => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);
        $newLecturingSchedule['creator_id'] = auth()->id();

        $lecturingSchedule = LecturingSchedule::create($newLecturingSchedule);

        return redirect()->route('lecturing_schedules.show', $lecturingSchedule);
    }

    public function show(LecturingSchedule $lecturingSchedule)
    {
        return view('lecturing_schedules.show', compact('lecturingSchedule'));
    }

    public function edit(LecturingSchedule $lecturingSchedule)
    {
        $this->authorize('update', $lecturingSchedule);

        return view('lecturing_schedules.edit', compact('lecturingSchedule'));
    }

    public function update(Request $request, LecturingSchedule $lecturingSchedule)
    {
        $this->authorize('update', $lecturingSchedule);

        $lecturingScheduleData = $request->validate([
            'title'       => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);
        $lecturingSchedule->update($lecturingScheduleData);

        return redirect()->route('lecturing_schedules.show', $lecturingSchedule);
    }

    public function destroy(Request $request, LecturingSchedule $lecturingSchedule)
    {
        $this->authorize('delete', $lecturingSchedule);

        $request->validate(['lecturing_schedule_id' => 'required']);

        if ($request->get('lecturing_schedule_id') == $lecturingSchedule->id && $lecturingSchedule->delete()) {
            return redirect()->route('lecturing_schedules.index');
        }

        return back();
    }
}
