<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $array)
 * @property string $value
 */
class Token extends Model
{
    use SoftDeletes;

    public $timestamps = true;

    protected $fillable = [
        'token_type_id',
        'value',
        'account_id',
        'api_service_id',
    ];

    public function apiService(): BelongsTo
    {
        return $this->belongsTo(ApiService::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function tokenType(): BelongsTo
    {
        return $this->belongsTo(TokenType::class);
    }

    public function getTokenTypeName(): ?string
    {
        return $this->tokenType()->first()->name ?? null;
    }
}
