<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $array)
 */
class ApiService extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'host',
        'supported_token_types',
        'endpoints',
    ];

    protected $casts = [
        'endpoints' => 'array',
        'supported_token_types' => 'array',
    ];

    public function tokens(): HasMany
    {
        return $this->hasMany(Token::class);
    }

    public function endpoints(): HasMany
    {
        return $this->hasMany(Endpoint::class);
    }

    public function addEndpoints(array $newEndpoints): bool
    {
        $endpoints = $this->endpoints ?? [];

        foreach ($newEndpoints as $endpoint) {
            if (!in_array($endpoint, $endpoints)) {
                $endpoints[] = $endpoint;
            }
        }

        $this->endpoints = $endpoints;
        return $this->save();
    }
}
