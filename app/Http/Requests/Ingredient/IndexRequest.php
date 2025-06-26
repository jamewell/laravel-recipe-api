<?php

namespace App\Http\Requests\Ingredient;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class IndexRequest
 *
 * @property int|null $category_id
 * @property string|null $search
 * @property string|null $sort_by
 * @property string|null $sort_direction
 * @property int|null $per_page
 */
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
            'category_id' => 'nullable|integer|exists:ingredient_categories,id',
            'search' => 'nullable|string|max:255',
            'sort_by' => 'nullable|string|in:name,created_at,updated_at',
            'sort_direction' => 'nullable|string|in:asc,desc',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }
}
