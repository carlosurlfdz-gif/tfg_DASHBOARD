<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAcceso extends Model
{
    protected $table = 'log_acceso'; 

    protected $fillable = [
        'id_log','id_usuario','username_intentado','ip_origen','user_agent','resultado','detalle','fecha_acceso'
    ]; 
}