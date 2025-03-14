<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Todo extends Model
{
    protected $fillable = [
        'title',
        'description',
        'is_done',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
