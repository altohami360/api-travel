<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TourListRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'dataFrom' => ['required', 'data'],
            'dataTo' => 'data',
            'priceFrom' => 'numeric',
            'priceTo' => 'numeric',
            'sortBy' => Rule::in(['price']),
            'sortOrder' => Rule::in(['des', 'desc'])
        ];
    }

    public function messages(): array
    {
        return [
            'sortBy' => "The 'sortBy' parameter accept only 'price' value",
            'sortOrder' => "The 'sortOrder' parameter accept only 'acs' or 'desc' value"
        ];
    }
}
