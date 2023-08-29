<?php

namespace App\Http\Controllers;

use App\Http\Requests\LecturingSchedules\CreateRequest;
use App\Http\Requests\LecturingSchedules\UpdateRequest;
use App\Models\LecturingSchedule;
use Illuminate\Http\Request;

class LecturingScheduleController extends Controller
{
    public function index(Request $request)
    {
        $lecturingScheduleQuery = LecturingSchedule::query();
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $yearMonth = $this->getYearMonth();
        if ($request->get('q')) {
            $lecturingScheduleQuery->where('lecturer_name', 'like', '%'.$request->get('q').'%');
        }
        $lecturingScheduleQuery->where('date', 'like', $yearMonth.'%');
        $lecturingScheduleQuery->orderBy('date')->orderBy('start_time');
        $lecturingSchedules = $lecturingScheduleQuery->get()->groupBy('audience_code');
        $audienceCodes = $this->getAudienceCodeList();

        return view('lecturing_schedules.index', compact('lecturingSchedules', 'year', 'month', 'audienceCodes'));
    }

    public function create()
    {
        $this->authorize('create', new LecturingSchedule);
        $audienceCodes = [
            LecturingSchedule::AUDIENCE_PUBLIC => __('lecturing_schedule.audience_'.LecturingSchedule::AUDIENCE_PUBLIC),
            LecturingSchedule::AUDIENCE_MUSLIMAH => __('lecturing_schedule.audience_'.LecturingSchedule::AUDIENCE_MUSLIMAH),
        ];

        return view('lecturing_schedules.create', compact('audienceCodes'));
    }

    public function store(CreateRequest $lecturingScheduleCreateForm)
    {
        $lecturingSchedule = $lecturingScheduleCreateForm->save();
        flash(__('lecturing_schedule.created'), 'success');

        return redirect()->route('lecturing_schedules.show', $lecturingSchedule);
    }

    public function show(LecturingSchedule $lecturingSchedule)
    {
        if (in_array($lecturingSchedule->audience_code, [LecturingSchedule::AUDIENCE_FRIDAY])) {
            return redirect()->route('friday_lecturing_schedules.show', $lecturingSchedule);
        }

        return view('lecturing_schedules.show', compact('lecturingSchedule'));
    }

    public function edit(LecturingSchedule $lecturingSchedule)
    {
        $this->authorize('update', $lecturingSchedule);

        $audienceCodes = [
            LecturingSchedule::AUDIENCE_PUBLIC => __('lecturing_schedule.audience_'.LecturingSchedule::AUDIENCE_PUBLIC),
            LecturingSchedule::AUDIENCE_MUSLIMAH => __('lecturing_schedule.audience_'.LecturingSchedule::AUDIENCE_MUSLIMAH),
        ];

        return view('lecturing_schedules.edit', compact('lecturingSchedule', 'audienceCodes'));
    }

    public function update(UpdateRequest $lecturingScheduleUpdateForm, LecturingSchedule $lecturingSchedule)
    {
        $lecturingSchedule->update($lecturingScheduleUpdateForm->validated());
        flash(__('lecturing_schedule.updated'), 'success');

        return redirect()->route('lecturing_schedules.show', $lecturingSchedule);
    }

    public function destroy(Request $request, LecturingSchedule $lecturingSchedule)
    {
        $this->authorize('delete', $lecturingSchedule);

        $request->validate(['lecturing_schedule_id' => 'required']);

        if ($request->get('lecturing_schedule_id') == $lecturingSchedule->id && $lecturingSchedule->delete()) {
            flash(__('lecturing_schedule.deleted'), 'success');
            return redirect()->route('lecturing_schedules.index');
        }

        flash(__('lecturing_schedule.undeleted'), 'error');

        return back();
    }
}
