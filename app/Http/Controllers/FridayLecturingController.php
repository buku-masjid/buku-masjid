<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lecturings\CreateFridayRequest;
use App\Http\Requests\Lecturings\FridayUpdateRequest;
use App\Models\Lecturing;

class FridayLecturingController extends Controller
{
    public function create()
    {
        $this->authorize('create', new Lecturing);

        return view('friday_lecturings.create');
    }

    public function store(CreateFridayRequest $lecturingCreateForm)
    {
        $lecturing = $lecturingCreateForm->save();
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

    public function update(FridayUpdateRequest $request, Lecturing $lecturing)
    {
        $lecturingData = $request->validated();
        $lecturing->update($lecturingData);
        flash(__('lecturing.updated'), 'success');

        return redirect()->route('friday_lecturings.show', $lecturing);
    }
}
