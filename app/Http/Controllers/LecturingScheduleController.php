<?php

namespace App\Http\Controllers;

use App\Models\LecturingSchedule;
use Illuminate\Http\Request;
use App\Http\Requests\LecturingSchedules\CreateRequest;
use App\Http\Requests\LecturingSchedules\UpdateRequest;

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

    public function store(CreateRequest $lecturingScheduleCreateForm)
    {
        $lecturingSchedule = $lecturingScheduleCreateForm->save();

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

    public function update(UpdateRequest $lecturingScheduleUpdateForm, LecturingSchedule $lecturingSchedule)
    {
        $lecturingSchedule->update($lecturingScheduleUpdateForm->validated());

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
