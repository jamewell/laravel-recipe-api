<?php

namespace App\Http\Requests\Recipe;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kitchen' => 'sometimes|integer|exists:kitchens,id',
            'servings' => 'sometimes|integer|min:1',
            'min_servings' => 'sometimes|integer|min:1',
            'max_servings' => 'sometimes|integer|min:1',
            'prep_time' => 'sometimes|integer|min:0',
            'max_prep_time' => 'sometimes|integer|min:0',
            'cook_time' => 'sometimes|integer|min:0',
            'max_cook_time' => 'sometimes|integer|min:0',
            'title' => 'sometimes|string|nullable',
        ];
    }
}
