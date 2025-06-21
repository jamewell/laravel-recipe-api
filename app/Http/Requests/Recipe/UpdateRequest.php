<?php

namespace App\Http\Requests\Recipe;

use App\Models\Recipe;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $recipes = Recipe::find($this->route('recipe'));

        return $recipes?->first()?->user_id === $this->user()?->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'kitchen_id' => 'sometimes|integer|exists:kitchens,id',
            'prep_time' => 'sometimes|integer|min:0',
            'cook_time' => 'sometimes|integer|min:0',
            'servings' => 'sometimes|integer|min:1',
            'img_url' => 'sometimes|nullable|url',
            'ingredients' => 'sometimes|array',
            'ingredients.*.id' => 'required|integer|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:0',
            'instructions' => 'sometimes|array',
            'instructions.*.description' => 'required|string|max:1000',
        ];
    }
}
