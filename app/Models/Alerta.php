<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alerta extends Model
{
    protected $table = 'alertas';
    protected $primaryKey = 'id_alerta';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_alerta', 'id_origen', 'event_uuid', 'flow_id', 'timestamp_evento', 'event_type', 'in_iface',
        'src_ip', 'src_port', 'dest_ip', 'dest_port', 'proto', 'app_proto', 'direction', 'accion',
        'firma_id', 'firma', 'categoria', 'severity', 'severity_label', 'mensaje', 'estado', 'payload',
        'json_crudo', 'procesada', 'created_at', 'updated_at'
    ]; 

    public static function prioridadTexto() {
        return [1=> "CRÍTICA", 2=> "ALTA", 3=> "MEDIA", 4=> "BAJA"];
    }
}
