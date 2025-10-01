<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Endpoint extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];
    public function apiService(): BelongsTo
    {
        return $this->belongsTo(ApiService::class);
    }
}
