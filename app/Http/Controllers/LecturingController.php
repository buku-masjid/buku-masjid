<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lecturings\CreateRequest;
use App\Http\Requests\Lecturings\UpdateRequest;
use App\Models\Lecturing;
use Illuminate\Http\Request;

class LecturingController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view-any', new Lecturing);

        $lecturingQuery = Lecturing::query();
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $yearMonth = $this->getYearMonth();
        if ($request->get('q')) {
            $lecturingQuery->where('lecturer_name', 'like', '%'.$request->get('q').'%');
        }
        $lecturingQuery->where('date', 'like', $yearMonth.'%');
        $lecturingQuery->orderBy('date')->orderBy('start_time');
        $lecturings = $lecturingQuery->get()->groupBy('audience_code');
        $audienceCodes = $this->getAudienceCodeList();

        return view('lecturings.index', compact('lecturings', 'year', 'month', 'audienceCodes'));
    }

    public function create()
    {
        $this->authorize('create', new Lecturing);

        $audienceCodes = [
            Lecturing::AUDIENCE_PUBLIC => __('lecturing.audience_'.Lecturing::AUDIENCE_PUBLIC),
            Lecturing::AUDIENCE_MUSLIMAH => __('lecturing.audience_'.Lecturing::AUDIENCE_MUSLIMAH),
        ];

        return view('lecturings.create', compact('audienceCodes'));
    }

    public function store(CreateRequest $lecturingCreateForm)
    {
        $lecturing = $lecturingCreateForm->save();
        flash(__('lecturing.created'), 'success');

        return redirect()->route('lecturings.show', $lecturing);
    }

    public function show(Lecturing $lecturing)
    {
        $this->authorize('view', $lecturing);

        if (in_array($lecturing->audience_code, [Lecturing::AUDIENCE_FRIDAY])) {
            return redirect()->route('friday_lecturings.show', $lecturing);
        }

        return view('lecturings.show', compact('lecturing'));
    }

    public function edit(Lecturing $lecturing)
    {
        $this->authorize('update', $lecturing);

        $audienceCodes = [
            Lecturing::AUDIENCE_PUBLIC => __('lecturing.audience_'.Lecturing::AUDIENCE_PUBLIC),
            Lecturing::AUDIENCE_MUSLIMAH => __('lecturing.audience_'.Lecturing::AUDIENCE_MUSLIMAH),
        ];

        return view('lecturings.edit', compact('lecturing', 'audienceCodes'));
    }

    public function update(UpdateRequest $lecturingUpdateForm, Lecturing $lecturing)
    {
        $lecturing->update($lecturingUpdateForm->validated());
        flash(__('lecturing.updated'), 'success');

        return redirect()->route('lecturings.show', $lecturing);
    }

    public function destroy(Request $request, Lecturing $lecturing)
    {
        $this->authorize('delete', $lecturing);

        $request->validate(['lecturing_id' => 'required']);

        if ($request->get('lecturing_id') == $lecturing->id && $lecturing->delete()) {
            flash(__('lecturing.deleted'), 'success');
            return redirect()->route('lecturings.index');
        }

        flash(__('lecturing.undeleted'), 'error');

        return back();
    }
}
