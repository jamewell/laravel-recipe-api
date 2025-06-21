<?php

namespace App\Http\Requests\Recipe;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        /** @var User|null $user */
        $user = $this->user();

        if (! $user) {
            abort(Response::HTTP_UNAUTHORIZED, 'Unauthorized');
        }

        // Ensure that the ingredients and instructions are arrays
        $this->merge([
            'user_id' => $user->id,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'kitchen_id' => 'required|exists:kitchens,id',
            'prep_time' => 'nullable|integer|min:0',
            'cook_time' => 'nullable|integer|min:0',
            'servings' => 'nullable|integer|min:1',
            'ingredients' => 'required|array',
            'ingredients.*.id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:0.01',
            'ingredients.*.unit_id' => 'required|exists:unit_of_measurements,id',
            'ingredients.*.notes' => 'nullable|string|max:255',
            'instructions' => 'required|array',
            'instructions.*.description' => 'required|string|max:255',
        ];
    }
}
