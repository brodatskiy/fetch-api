<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Str;

/**
 * @method static create(array $array)
 */
class Account extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public function resolveRelation($modelName)
    {
        $methodName = strtolower(Str::plural($modelName));

        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        }

        return null;
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function tokens(): HasMany
    {
        return $this->hasMany(Token::class);
    }

    public function incomes(): MorphToMany
    {
        return $this->morphedByMany(Income::class, 'accountable');
    }

    public function sales(): MorphToMany
    {
        return $this->morphedByMany(Sale::class, 'accountable');
    }

    public function orders(): MorphToMany
    {
        return $this->morphedByMany(Order::class, 'accountable');
    }

    public function stocks(): MorphToMany
    {
        return $this->morphedByMany(Stock::class, 'accountable');
    }
}
