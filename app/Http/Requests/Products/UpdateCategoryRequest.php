<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'          => ['nullable', 'string', 'min:3', 'max:55'],
            'description'   => ['nullable', 'string'],
            'path'          => ['nullable', 'image', 'max:2500'],
            'parent_id'     => ['nullable', 'exists:categories,id']
        ];
    }
}
