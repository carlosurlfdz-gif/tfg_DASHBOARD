<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alerta_estado_historial extends Model
{
    protected $table = 'alerta_estado_historial'; 

    protected $fillable = [
        'id_historial','id_alerta','id_usuario','estado_anterior','estado_nuevo','comentario','fecha_cambio'
    ]; 
}