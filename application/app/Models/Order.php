<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $table = 'orders';
    protected $guarded = [];

    public function accounts(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
