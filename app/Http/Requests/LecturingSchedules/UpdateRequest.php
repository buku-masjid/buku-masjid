<?php

namespace App\Http\Requests\LecturingSchedules;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('update', $this->route('lecturing_schedule'));
    }

    public function rules()
    {
        return [
            'title'       => 'required|max:60',
            'description' => 'nullable|max:255',
        ];
    }
}
