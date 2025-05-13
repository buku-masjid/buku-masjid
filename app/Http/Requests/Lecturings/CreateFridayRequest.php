<?php

namespace App\Http\Requests\Lecturings;

use App\Models\Lecturing;
use App\Rules\Lecturings\FridayDate;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateFridayRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('create', new Lecturing);
    }

    public function rules()
    {
        return [
            'date' => [
                'required',
                'date_format:Y-m-d',
                new FridayDate,
                Rule::unique('lecturings', 'date')->where(function (Builder $query) {
                    $query->where('audience_code', 'friday');
                }),
            ],
            'start_time' => ['required', 'date_format:H:i'],
            'lecturer_name' => ['required', 'max:60'],
            'imam_name' => ['required', 'max:60'],
            'muadzin_name' => ['required', 'max:60'],
            'title' => ['nullable', 'max:60'],
            'video_link' => ['nullable', 'max:255'],
            'audio_link' => ['nullable', 'max:255'],
            'description' => ['nullable', 'max:255'],
        ];
    }

    public function save()
    {
        $newLecturing = $this->validated();
        $newLecturing['creator_id'] = auth()->id();
        $newLecturing['audience_code'] = 'friday';

        return Lecturing::create($newLecturing);
    }

    public function messages()
    {
        return [
            'date.unique' => __('validation.friday_lecturing.date.unique'),
        ];
    }
}
