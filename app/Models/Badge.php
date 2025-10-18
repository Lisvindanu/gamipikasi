<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Badge extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'emoji',
        'criteria_type',
        'criteria_value',
        'auto_award',
    ];

    protected $casts = [
        'auto_award' => 'boolean',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_badges')
            ->withPivot('earned_at');
    }
}
