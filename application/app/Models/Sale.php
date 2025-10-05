<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    protected $table = 'sales';
    protected $guarded = [];

    public function accounts(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
