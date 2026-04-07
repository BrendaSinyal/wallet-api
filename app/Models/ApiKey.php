<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
        protected $fillable = [
        'key_hash',
        'key_prefix',
        'name',
        'is_active',
        'last_used_at',
    ];
}
