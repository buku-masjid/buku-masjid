<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class PhoneNumberRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = trim($value);

        if (Str::contains($value, ' ')) {
            $fail('The :attribute must not contain spaces.');
        }

        // Check if starts with optional + and followed by numbers only
        if (!preg_match('/^\+?\d+$/', $value)) {
            $fail('The :attribute must contain only numbers and may start with +.');
        }

        // Check length (excluding + if present)
        $numberOnly = ltrim($value, '+');
        $length = strlen($numberOnly);

        if ($length < 10 || $length > 15) {
            $fail('The :attribute must be between 10 and 15 digits.');
        }
    }
}
