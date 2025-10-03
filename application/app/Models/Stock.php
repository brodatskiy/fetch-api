<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Stock extends Model
{
    protected $table = 'stocks';
    protected $guarded = [];

    public function accounts(): MorphToMany
    {
        return $this->morphToMany(Account::class, 'accountable');
    }
}
