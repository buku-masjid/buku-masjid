<?php

namespace App\Http\Requests\LecturingSchedules;

use App\Models\LecturingSchedule;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('create', new LecturingSchedule);
    }

    public function rules()
    {
        return [
            'title'       => 'required|max:60',
            'description' => 'nullable|max:255',
        ];
    }

    public function save()
    {
        $newLecturingSchedule = $this->validated();
        $newLecturingSchedule['creator_id'] = auth()->id();

        return LecturingSchedule::create($newLecturingSchedule);
    }
}
