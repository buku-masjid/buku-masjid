<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class PhoneNumberRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!Str::startsWith($value, '08')) {
            $fail(__('validation.donor.phone.starts_with', ['starting_number' => '08']));
        }

        if (Str::contains($value, ' ')) {
            $fail(__('validation.numeric'));
        }

        if (!preg_match('/^[0-9]+$/', $value)) {
            $fail(__('validation.numeric'));
        }
    }
}
