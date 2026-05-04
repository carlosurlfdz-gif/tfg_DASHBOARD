<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cache extends Model
{
    protected $table = 'cache'; 

    protected $fillable = [
        'key','value','expiration'
    ]; 
}