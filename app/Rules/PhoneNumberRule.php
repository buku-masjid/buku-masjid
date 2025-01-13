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
            $fail(__('validation.numeric'));
        }

        if (!preg_match('/^\+?\d+$/', $value)) {
            $fail(__('validation.partner.phone.starts_with'));
        }

        // Check length (excluding + if present)
        $numberOnly = ltrim($value, '+');
        $length = strlen($numberOnly);

        if ($length < 10 || $length > 15) {
            $fail(__('validation.partner.phone.between', ['min' => 10, 'max' => 15]));
        }
    }
}
