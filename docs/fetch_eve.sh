#!/usr/bin/bash
# Recibe solo alertas de Suricata desde pfSense VM1 via SSH
# VM3 Ubuntu 192.168.10.20 -> VM1 pfSense 192.168.10.1
 
SSH_KEY="/root/.ssh/id_siem_project"
PFSENSE="xespinosa@192.168.10.1"
SSH_PORT="2222"
EVE_REMOTE="/var/log/suricata/suricata_em03351/eve.json"
EVE_LOCAL="/var/log/suricata/eve_remote.json"
 
ssh -n -i $SSH_KEY \
    -p $SSH_PORT \
    -o StrictHostKeyChecking=no \
    -o ServerAliveInterval=30 \
    -o ServerAliveCountMax=3 \
    $PFSENSE \
    "tail -F $EVE_REMOTE | grep --line-buffered -a 'event_type.*alert'" >> $EVE_LOCAL
