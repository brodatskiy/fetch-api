<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Income extends Model
{
    protected $table = 'incomes';
    protected $guarded = [];

    public function accounts(): MorphToMany
    {
        return $this->morphToMany(Account::class, 'accountable');
    }
}
