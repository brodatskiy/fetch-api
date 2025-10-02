<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $array)
 */
class Endpoint extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'urn',
        'model',
        'api_service_id',
    ];

    protected $casts = [
        'model' => \App\Enum\Model::class,
    ];

    public function apiService(): BelongsTo
    {
        return $this->belongsTo(ApiService::class);
    }
}
