<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $array)
 * @method static find(array|string|null $argument)
 */
class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }
}
