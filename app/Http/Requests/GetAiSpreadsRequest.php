<?php

namespace App\Http\Requests;

class GetAiSpreadsRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'images' => 'required',
            'images_prompt' => 'required|string',
            'spreads_prompt' => 'required|string',
            'data_id' => 'required|integer',
        ];
    }

    /**
     * Get the validated data from the request.
     *
     * @param  null  $key
     * @param  null  $default
     */
    public function validated($key = null, $default = null): array
    {
        return $this->only(['images', 'images_prompt', 'spreads_prompt', 'data_id']);
    }
}
