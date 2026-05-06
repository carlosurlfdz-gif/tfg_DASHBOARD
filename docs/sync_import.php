<?php
// Configuración Horaria
date_default_timezone_set('Europe/Madrid');

// Configuración de la Base de Datos (VM2)
$db_host = 'localhost';
$db_name = 'xx_bd';
$db_user = 'root';
$db_pass = '';

// Rutas de archivos (VM3)
$log_file = 'C:/Users/carlo/Downloads/eve (3).json';
$pos_file = '/var/log/suricata/eve_remote.pos'; // Guarda por dónde nos quedamos leyendo

try {
    // 1. Conexión a MariaDB
    $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    // Preparar la consulta SQL (Usamos INSERT IGNORE para evitar duplicados si algo falla)
    $sql = "INSERT IGNORE INTO alertas (
                id_origen, event_uuid, flow_id, timestamp_evento, event_type, in_iface, 
                src_ip, src_port, dest_ip, dest_port, proto, app_proto, severity, 
                severity_label, categoria, firma_id, firma, mensaje, payload, json_crudo
            ) VALUES (
                1, :event_uuid, :flow_id, :timestamp_evento, :event_type, :in_iface, 
                :src_ip, :src_port, :dest_ip, :dest_port, :proto, :app_proto, :severity, 
                :severity_label, :categoria, :firma_id, :firma, :mensaje, :payload, :json_crudo
            )";
    $stmt = $pdo->prepare($sql);

    // 2. Abrir archivo de logs y buscar última posición
    if (!file_exists($log_file)) die("El archivo de log no existe aún.\n");
    $file = fopen($log_file, 'r');
    
    $last_pos = file_exists($pos_file) ? (int)file_get_contents($pos_file) : 0;
    
    // Si el archivo es más pequeño que la última posición (ej. se rotó o borró), empezamos de cero
    clearstatcache();
    if (filesize($log_file) < $last_pos) $last_pos = 0;
    
    fseek($file, $last_pos);

    // 3. Leer línea por línea
    $procesados = 0;
    while (($line = fgets($file)) !== false) {
        $json = json_decode($line, true);
        
        if (json_last_error() === JSON_ERROR_NONE && isset($json['event_type']) && $json['event_type'] === 'alert') {
            
            // Transformar timestamp de Suricata a MySQL datetime
            $datetime = date('Y-m-d H:i:s', strtotime($json['timestamp']));
            
            // Generar un UUID determinista basado en la línea para evitar duplicados en la BD
            $hash = md5($line);
            $uuid = sprintf('%s-%s-%s-%s-%s', substr($hash, 0, 8), substr($hash, 8, 4), substr($hash, 12, 4), substr($hash, 16, 4), substr($hash, 20, 12));

            // Mapear la severidad (1: Alta, 2: Media, 3: Baja)
            $sev_num = $json['alert']['severity'] ?? null;
            $sev_label = 'Baja';
            if ($sev_num == 1) $sev_label = 'Alta';
            elseif ($sev_num == 2) $sev_label = 'Media';
            
            // Obtener Payload (priorizar el imprimible si existe)
            $payload = $json['payload_printable'] ?? ($json['payload'] ?? null);

            // Ejecutar inserción manejando campos nulos (ej. ICMP no tiene puertos)
            $stmt->execute([
                ':event_uuid'       => $uuid,
                ':flow_id'          => $json['flow_id'] ?? null,
                ':timestamp_evento' => $datetime,
                ':event_type'       => $json['event_type'],
                ':in_iface'         => $json['in_iface'] ?? null,
                ':src_ip'           => $json['src_ip'],
                ':src_port'         => $json['src_port'] ?? null,
                ':dest_ip'          => $json['dest_ip'],
                ':dest_port'        => $json['dest_port'] ?? null,
                ':proto'            => $json['proto'] ?? null,
                ':app_proto'        => $json['app_proto'] ?? null,
                ':severity'         => $sev_num,
                ':severity_label'   => $sev_label,
                ':categoria'        => $json['alert']['category'] ?? null,
                ':firma_id'         => $json['alert']['signature_id'] ?? null,
                ':firma'            => $json['alert']['signature'] ?? null,
                ':mensaje'          => $json['alert']['action'] ?? null,
                ':payload'          => $payload,
                ':json_crudo'       => trim($line)
            ]);
            $procesados++;
        }
    }

    // 4. Guardar la nueva posición para la próxima ejecución del Cron
    $new_pos = ftell($file);
    file_put_contents($pos_file, $new_pos);
    fclose($file);

    echo "Sincronización completada. Alertas insertadas: $procesados\n";

} catch (PDOException $e) {
    die("Error de Base de Datos: " . $e->getMessage() . "\n");
}
?>
