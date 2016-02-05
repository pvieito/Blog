---
layout: post
title: Instalación del Combo R 100
---

Hace unos días decidí cambiar de Movistar ADSL 10 Mbps a un Combo R 100 Mbps. Lo pedí el pasado miércoles a la mañana, a la tarde llamé para pedir la cita con el técnico y al día siguiente ya vinieron a hacer la instalación.

### Instalación

<figure>
<img alt="PTR de R" src="/media/2013/12/ptr.jpg" />
<figcaption>Lo que lleva el PTR dentro: un repartidor coaxial y un zócalo RJ para probar el teléfono.</figcaption>
</figure>

Básicamente suben un cable doble (coaxial para Internet y par de cobre para teléfono) desde un distribuidor externo hasta un Punto de Terminación de Red (PTR) que colocan dentro de casa. Ese PTR es una caja que simplemente tiene dos salidas coaxiales (internet y televisión) y una de teléfono.

### Router

La primera salida coaxial va directamente al router, mi modelo fue el que instalan con los Combos 100 y 200, un inesperado [Cisco EPC3928AD](http://particulares.mundo-r.com/es/mas/internet/equipos). Acostumbrado a los equipos que suele dar Movistar esto fue una bonita alegría. A parte de incluir todo los clásico (DHCP, IEEE802.11b/g/n, filtrado MAC, DDNS o DMZ) tiene también servidor de archivos (gracias a su USB), Media Server, configuración Wi-Fi avanzada (velocidad de transmisión, medición automática de ruido por canal, permite crear 2 radios completamente independientes) y el VPN de Cisco.

### Teléfono

Para conectar el teléfono simplemente desconectan la red del operador antiguo y enchufan el par de cobre de R a la red de teléfono interior. En ese momento todos los teléfonos ya funcionan con un número temporal que te dan mientras no se finaliza la portabilidad.

### Velocidad

<table>
<tr>
<td>Conexión</td>
<td>Contratada</td>
<td>Bajada</td>
<td>Subida</td>
</tr>
<tr>
<td><strong>Movistar</strong></td>
<td>10 Mbps</td>
<td>4,95 Mbps</td>
<td>0,54 Mbps</td>
</tr>
<tr>
<td><strong>R Combo</strong></td>
<td>100 Mbps</td>
<td>103,63 Mbps</td>
<td>9,36 Mbps</td>
</tr>
</table>

En su web dicen que garantizan hasta un 80% de la velocidad contratada por cable, y la verdad es que sí que cumplen. Conectado por Ethernet todos los test de velocidad que he realizado han superado los 100 Mbps que tengo contratados. Bastante sorprendente comparado con Movistar, que de los “hasta 10 Mbps” anunciados llegaban tan solo 5 Mbps.
