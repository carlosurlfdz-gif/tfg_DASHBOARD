<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alerta extends Model
{
    protected $table = 'alertas';
    protected $primaryKey = 'id_alerta';
    public $incrementing = true; 

    protected $fillable = [
        'id_alerta', 'timestamp_evento', 'flow_id', 'src_ip','src_port','dest_ip','dest_port','proto','app_proto','direction','accion','firma_id','firma','categoria','severity','estado','created_at','updated_at'
    ]; 

    public static function prioridadTexto() {
        return ['Critica' => 'CRÍTICA', 'Alta' => 'ALTA', 'Media' => 'MEDIA', 'Baja' => 'BAJA'];
    }

    
}
