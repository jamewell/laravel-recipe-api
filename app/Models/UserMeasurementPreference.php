<?php

namespace App\Models;

use App\Enums\UnitSystem;
use Database\Factories\UserMeasurementPreferenceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMeasurementPreference extends Model
{
    /** @use HasFactory<UserMeasurementPreferenceFactory> */
    use HasFactory;

    protected $fillable = [
        'system',
    ];

    protected $casts = [
        'system' => UnitSystem::class,
    ];

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
