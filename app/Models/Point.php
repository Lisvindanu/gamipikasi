<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Point extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'category',
        'value',
        'note',
        'given_by',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'given_by');
    }
}
