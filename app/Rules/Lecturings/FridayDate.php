<?php

namespace App\Rules\Lecturings;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FridayDate implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $date = Carbon::parse($value);
        if (strtolower($date->format('l')) !== 'friday') {
            $fail(__('validation.friday_lecturing.date.friday_only'));
        }
    }
}
