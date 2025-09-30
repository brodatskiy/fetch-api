<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Token extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public function apiService(): BelongsTo
    {
        return $this->belongsTo(ApiService::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function tokeType(): BelongsTo
    {
        return $this->belongsTo(TokenType::class);
    }
}
