---
title: Instalación del Combo R 100
lang: es
---

Hace unos días decidí cambiar de Movistar ADSL 10 Mbps a un Combo R 100 Mbps. Lo pedí el pasado miércoles a la mañana, a la tarde llamé para pedir la cita con el técnico y al día siguiente ya vinieron a hacer la instalación.

### Instalación

![PTR de R](/media/2013/12/ptr.jpg)
<figcaption>Lo que lleva el PTR dentro: un repartidor coaxial y un zócalo RJ para probar el teléfono.</figcaption>

Básicamente suben un cable doble (coaxial para Internet y par de cobre para teléfono) desde un distribuidor externo hasta un Punto de Terminación de Red (PTR) que colocan dentro de casa. Ese PTR es una caja que simplemente tiene dos salidas coaxiales (internet y televisión) y una de teléfono.

### Router

La primera salida coaxial va directamente al router, mi modelo fue el que instalan con los Combos 100 y 200, un inesperado [Cisco EPC3928AD](http://particulares.mundo-r.com/es/mas/internet/equipos). Acostumbrado a los equipos que suele dar Movistar esto fue una bonita alegría. A parte de incluir todo los clásico (DHCP, IEEE802.11b/g/n, filtrado MAC, DDNS o DMZ) tiene también un servidor de archivos (gracias a su puerto USB), Media Server, configuración Wi-Fi avanzada (velocidad de transmisión, medición automática de ruido por canal, permite crear 2 redes inalámbricas independientes) y el VPN de Cisco.

### Teléfono

Para conectar el teléfono simplemente desconectan la red del antiguo operador y enchufan el par de cobre de R a la red de teléfono interior. Desde ese momento todos los teléfonos ya funcionan con un número temporal que te dan mientras no se finaliza la portabilidad.

### Velocidad

| Conexión | Contratada |  Bajada | Subida
|----
| **Movistar** | 10 Mbps| 4,95 Mbps| 0,54 Mbps
| **R Combo** | 100 Mbps | 103,63 Mbps | 9,36 Mbps

En su web dicen que garantizan hasta un 80% de la velocidad contratada por cable, y la verdad es que sí que cumplen. Conectado por Ethernet todos los test de velocidad que he realizado han superado los 100 Mbps que tengo contratados. Bastante sorprendente comparado con Movistar, que de los “hasta 10 Mbps” anunciados me llegaban tan solo 5 Mbps.
