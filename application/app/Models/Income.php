<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Income extends Model
{
    protected $table = 'incomes';
    protected $guarded = [];

    public function accounts(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
