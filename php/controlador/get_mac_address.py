#!/usr/bin/env python
import cgi
import cgitb
import json
import psutil
import netifaces

cgitb.enable()

# Obtener la dirección MAC del cliente
def get_mac_address():
    # Obtener la lista de todas las interfaces de red disponibles en el sistema
    interfaces = psutil.net_if_addrs()

    # Recorrer la lista de interfaces de red y buscar la dirección MAC
    for interface_name, interface_addresses in interfaces.items():
        for address in interface_addresses:
            if address.family == netifaces.AF_LINK:
                return address.address

# Establecer el encabezado de respuesta para permitir el acceso desde cualquier origen
print("Content-Type: application/json")
print("Access-Control-Allow-Origin: *")
print()

# Obtener la dirección MAC del cliente
mac_address = get_mac_address()

# Enviar la dirección MAC como respuesta
print(json.dumps(mac_address))
