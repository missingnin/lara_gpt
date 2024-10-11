<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetAiSpreadsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'images' => 'required',
            'prompt' => 'required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'images.required' => 'Image URL is required',
            'prompt.required' => 'Prompt is required',
        ];
    }
}
