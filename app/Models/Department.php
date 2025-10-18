<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = [
        'name',
        'description',
        'head_id',
    ];

    public function head(): BelongsTo
    {
        return $this->belongsTo(User::class, 'head_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(User::class, 'department_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }
}
