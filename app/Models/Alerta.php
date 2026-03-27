<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alerta extends Model
{
    protected $table = 'alertas'; 

    protected $fillable = [
        'id_alerta', 'timestamp_evento', 'flow_id', 'src_ip','src_port','dest_ip','dest_port','proto','app_proto','direction','accion','firma_id','firma','categoria','severidad','estado','created_at','updated_at'
    ]; 
}
