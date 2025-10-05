<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    protected $table = 'stocks';
    protected $guarded = [];

    public function accounts(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
