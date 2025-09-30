<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiService extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public function tokens(): HasMany
    {
        return $this->hasMany(Token::class);
    }
}
