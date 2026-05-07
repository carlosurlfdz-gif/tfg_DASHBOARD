## 1. fetch_eve.sh (Extracción en tiempo real)
Este script de Bash es el responsable de la ingesta de datos.
Funcionamiento: Establece un túnel SSH (puerto 2222) hacia pfSense.
Filtro: Utiliza tail -F combinado con grep para extraer únicamente las líneas del JSON original que contienen el campo event_type: alert.
Destino: Redirige el flujo de alertas filtradas al archivo local eve_remote.json en la VM3


## 2. fetch-eve.service (Persistencia del servicio)
Archivo de configuración de systemd para garantizar que la recolección sea ininterrumpida.
Función: Ejecuta fetch_eve.sh como un servicio del sistema.  
Robustez: Configurado con Restart=always y un intervalo de 10 segundos para reconexión automática en caso de caída del túnel SSH


## 3. sync_import.php (Procesamiento y ETL)
Script de PHP que actúa como el motor de importación hacia la base de datos MariaDB.
Control de posición: Utiliza un archivo .pos para recordar la última línea leída y evitar duplicados o saltos en los datos.
Normalización: * Transforma el timestamp de Suricata al formato compatible con MySQL (Y-m-d H:i:s).
Mapea niveles de severidad (1: Alta, 2: Media, 3: Baja).
Genera un UUID determinista basado en el contenido de la línea para asegurar la integridad de la tabla alertas.
Inserción: Se conecta a la VM2 (192.168.10.10) e inserta los datos de forma segura mediante sentencias preparadas (PDO).


## 🚀 Cómo desplegar los scripts en el proyecto SIEM

Sigue estos pasos en la **VM3 (Ubuntu)**:

1.  **Copiar script:** Copia `fetch_eve.sh` a `/usr/local/bin/` y dale permisos de ejecución:
    bash
    cp fetch_eve.sh /usr/local/bin/
    chmod +x /usr/local/bin/fetch_eve.sh
    

2.  **Instalar servicio:** Instala y activa el servicio systemd:
    bash
    cp fetch-eve.service /etc/systemd/system/
    systemctl enable --now fetch-eve.service
    

3.  **Configurar Cron:** Configura un Cron job para ejecutar `sync_import.php` cada minuto.

# 🛡️ SIEM Dashboard: Documentación de Scripts y Flujo de Datos

![Diagrama de Arquitectura del SIEM](./images/arquitectura.png)

Este repositorio contiene la lógica de extracción, procesamiento e inserción...
