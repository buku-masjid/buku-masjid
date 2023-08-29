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
            'audience_code' => ['required', 'max:15'],
            'date' => ['required', 'date_format:Y-m-d'],
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
        ];
    }

    public function save()
    {
        $newLecturingSchedule = $this->validated();
        $newLecturingSchedule['creator_id'] = auth()->id();

        return LecturingSchedule::create($newLecturingSchedule);
    }
}
