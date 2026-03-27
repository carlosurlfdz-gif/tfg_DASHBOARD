<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloqueoIp extends Model
{
    protected $table = 'bloqueo_ip'; 

    protected $fillable = [
        'id_bloqueo', 'id_alerta', 'id_usuario','ip_bloqueada','motivo','origen_bloqueo','estado','fecha_bloqueo','fecha_desbloqueo'
    ]; 
}