<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
        protected $fillable = [
        'key',
        'name',
        'is_active',
    ];
}
