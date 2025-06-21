<?php

namespace App\Http\Controllers\Recipe;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DestroyController extends Controller
{
    public function __invoke(Request $request, Recipe $recipe): JsonResponse
    {
        if ($request->user()?->id !== $recipe->user_id) {
            abort(
                Response::HTTP_UNAUTHORIZED,
                'You are not authorized to delete this recipe.'
            );
        }

        $recipe->delete();

        return response()->json(['message' => 'Recipe deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}
