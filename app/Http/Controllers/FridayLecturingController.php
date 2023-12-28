<?php

namespace App\Http\Controllers;

use App\Models\Lecturing;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FridayLecturingController extends Controller
{
    public function create()
    {
        $this->authorize('create', new Lecturing);

        return view('friday_lecturings.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', new Lecturing);

        $newLecturing = $request->validate([
            'date' => [
                'required',
                'date_format:Y-m-d',
                Rule::unique('lecturings', 'date')->where(function (Builder $query) {
                    $query->where('audience_code', 'friday');
                }),
            ],
            'start_time' => ['required', 'date_format:H:i'],
            'lecturer_name' => ['required', 'max:60'],
            'title' => ['nullable', 'max:60'],
            'video_link' => ['nullable', 'max:255'],
            'audio_link' => ['nullable', 'max:255'],
            'description' => ['nullable', 'max:255'],
        ], [
            'date.unique' => __('validation.friday_lecturing.date.unique'),
        ]);
        $newLecturing['creator_id'] = auth()->id();
        $newLecturing['audience_code'] = 'friday';

        $lecturing = Lecturing::create($newLecturing);
        flash(__('lecturing.created'), 'success');

        return redirect()->route('friday_lecturings.show', $lecturing);
    }

    public function show(Lecturing $lecturing)
    {
        $this->authorize('view', $lecturing);

        if (!in_array($lecturing->audience_code, [Lecturing::AUDIENCE_FRIDAY])) {
            return redirect()->route('lecturings.show', $lecturing);
        }

        return view('friday_lecturings.show', compact('lecturing'));
    }

    public function edit(Lecturing $lecturing)
    {
        $this->authorize('update', $lecturing);

        return view('friday_lecturings.edit', compact('lecturing'));
    }

    public function update(Request $request, Lecturing $lecturing)
    {
        $this->authorize('update', $lecturing);

        $lecturingData = $request->validate([
            'date' => [
                'required',
                'date_format:Y-m-d',
                Rule::unique('lecturings', 'date')->ignore($lecturing->id)->where(function (Builder $query) {
                    $query->where('audience_code', 'friday');
                }),
            ],
            'start_time' => ['required', 'date_format:H:i'],
            'lecturer_name' => ['required', 'max:60'],
            'title' => ['nullable', 'max:60'],
            'video_link' => ['nullable', 'max:255'],
            'audio_link' => ['nullable', 'max:255'],
            'description' => ['nullable', 'max:255'],
        ], [
            'date.unique' => __('validation.friday_lecturing.date.unique'),
        ]);
        $lecturing->update($lecturingData);
        flash(__('lecturing.updated'), 'success');

        return redirect()->route('friday_lecturings.show', $lecturing);
    }
}
