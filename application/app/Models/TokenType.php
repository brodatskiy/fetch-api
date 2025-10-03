<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 */
class TokenType extends Model
{
    protected $fillable = [
        'name',
    ];

}
