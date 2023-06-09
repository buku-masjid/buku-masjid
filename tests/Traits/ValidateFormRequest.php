<?php

namespace Tests\Traits;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Validator;

trait ValidateFormRequest
{
    protected function assertValidationPasses(FormRequest $formRequest, array $attributes)
    {
        $validator = $this->getValidator($formRequest, $attributes);
        $this->assertTrue($validator->passes(), 'Validation should be passed, but it fails.');

        return $validator;
    }

    protected function assertValidationFails(FormRequest $formRequest, array $attributes, Closure $callback = null)
    {
        $validator = $this->getValidator($formRequest, $attributes);
        $this->assertTrue($validator->fails(), 'Validation should be fails, but it passed.');

        if ($callback) {
            call_user_func($callback, $validator->getMessageBag());
        }

        return $validator;
    }

    protected function getValidator(FormRequest $formRequest, array $attributes)
    {
        return Validator::make($attributes, $formRequest->rules(), $formRequest->messages());
    }
}
