<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

/**
 * Base request class that handles failed validation attempts.
 */
class BaseRequest extends FormRequest
{
    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * The validator instance that failed validation.
     *
     * @return void
     *
     * @throws HttpResponseException
     * When validation fails, an HTTP response exception is thrown with a JSON response containing the validation errors.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                ['error' => $validator->messages()],
                422)
        );
    }
}
