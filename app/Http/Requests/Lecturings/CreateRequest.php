<?php

namespace App\Http\Requests\Lecturings;

use App\Models\Lecturing;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('create', new Lecturing);
    }

    public function rules()
    {
        $isImamRequired = in_array($this->get('audience_code'), [Lecturing::AUDIENCE_TARAWIH]);

        return [
            'audience_code' => ['required', 'max:15'],
            'date' => ['required', 'date_format:Y-m-d'],
            'start_time' => ['required', 'date_format:H:i'],
            'start_time' => [
                'required',
                'date_format:H:i',
                Rule::unique('lecturings')->where(function (Builder $query) {
                    $query->where('date', $this->get('date'));
                }),
            ],
            'end_time' => ['nullable', 'date_format:H:i'],
            'time_text' => [
                'nullable',
                'max:20',
                Rule::unique('lecturings')->where(function (Builder $query) {
                    $query->where('date', $this->get('date'));
                }),
            ],
            'lecturer_name' => ['required', 'max:60'],
            'imam_name' => [$isImamRequired ? 'required' : 'nullable', 'max:60'],
            'muadzin_name' => ['nullable', 'max:60'],
            'title' => ['nullable', 'max:60'],
            'book_title' => ['nullable', 'max:60'],
            'book_writer' => ['nullable', 'max:60'],
            'book_link' => ['nullable', 'max:255'],
            'video_link' => ['nullable', 'max:255'],
            'audio_link' => ['nullable', 'max:255'],
            'description' => ['nullable', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'start_time.unique' => __('validation.lecturing.start_time.unique', ['start_time' => $this->get('start_time')]),
            'time_text.unique' => __('validation.lecturing.time_text.unique', ['time_text' => $this->get('time_text')]),
        ];
    }

    public function save()
    {
        $newLecturing = $this->validated();
        $newLecturing['creator_id'] = auth()->id();

        return Lecturing::create($newLecturing);
    }
}
