<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $array)
 * @method static find(array|string|null $argument)
 */
class ApiService extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'host',
        'supported_token_types',
    ];

    protected $casts = [
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

    public function tokenTypes(): BelongsToMany
    {
        return $this->belongsToMany(TokenType::class);
    }
}
