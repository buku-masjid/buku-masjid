<?php

namespace App\Http\Requests\Lecturings;

use App\Rules\Lecturings\FridayDate;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FridayUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('update', $this->route('lecturing'));
    }

    public function rules()
    {
        $lecturing = $this->route('lecturing');

        return [
            'date' => [
                'required',
                'date_format:Y-m-d',
                new FridayDate,
                Rule::unique('lecturings', 'date')->ignore($lecturing->id)->where(function (Builder $query) {
                    $query->where('audience_code', 'friday');
                }),
            ],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
            'time_text' => ['nullable', 'max:20'],
            'lecturer_name' => ['required', 'max:60'],
            'title' => ['nullable', 'max:60'],
            'book_title' => ['nullable', 'max:60'],
            'book_writer' => ['nullable', 'max:60'],
            'book_link' => ['nullable', 'max:255'],
            'video_link' => ['nullable', 'max:255'],
            'audio_link' => ['nullable', 'max:255'],
            'description' => ['nullable', 'max:255'],
            'imam_name' => ['required', 'max:60'],
            'muadzin_name' => ['required', 'max:60'],
        ];
    }

    public function messages()
    {
        return [
            'date.unique' => __('validation.friday_lecturing.date.unique'),
        ];
    }
}
