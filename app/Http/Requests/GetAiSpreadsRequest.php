<?php

namespace App\Http\Requests;

class GetAiSpreadsRequest extends BaseRequest
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
            'data_id' => 'required|integer',
        ];
    }
}
