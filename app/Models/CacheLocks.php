<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CacheLocks extends Model
{
    protected $table = 'cache_locks'; 

    protected $fillable = [
        'key','owner','expiration'
    ]; 
}