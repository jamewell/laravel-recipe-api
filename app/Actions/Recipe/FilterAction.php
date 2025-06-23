<?php

namespace App\Actions\Recipe;

use Illuminate\Contracts\Database\Eloquent\Builder;

class FilterAction
{
    /**
     * Apply filters to the query.
     *
     * @param  array<string, mixed>  $filters
     */
    public function execute(Builder $query, array $filters): Builder
    {
        return $query
            ->when(
                isset($filters['kitchen']),
                fn ($query) => $query->where('kitchen_id', $filters['kitchen'])
            )
            ->when(
                isset($filters['servings']),
                fn ($query) => $query->where('servings', $filters['servings'])
            )
            ->when(
                isset($filters['min_servings']),
                fn ($query) => $query->where('servings', '>=', $filters['min_servings'])
            )
            ->when(
                isset($filters['max_servings']),
                fn ($query) => $query->where('servings', '<=', $filters['max_servings'])
            )
            ->when(
                isset($filters['prep_time']),
                fn ($query) => $query->where('prep_time', $filters['prep_time'])
            )
            ->when(
                isset($filters['max_prep_time']),
                fn ($query) => $query->where('prep_time', '<=', $filters['max_prep_time'])
            )
            ->when(
                isset($filters['cook_time']),
                fn ($query) => $query->where('cook_time', $filters['cook_time'])
            )
            ->when(
                isset($filters['max_cook_time']),
                fn ($query) => $query->where('cook_time', '<=', $filters['max_cook_time'])
            )
            ->when(
                isset($filters['title']) && $filters['title'] !== '',
                fn ($query) => $query->where('title', 'like', '%'.$filters['title'].'%')
            );
    }
}
