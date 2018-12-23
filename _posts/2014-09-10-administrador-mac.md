---
title: Cómo recuperar acceso de administrador en un Mac
lang: es
---

Siguiendo estas instrucciones se puede recuperar el acceso de administrador de un Mac sin eliminar otras cuentas o borrar el disco. Este método no funcionará si el disco duro está cifrado con FileVault 2.

* Reiniciar el Mac.
* Antes de escuchar el sonido de arranque hay que pulsar Comando+S para entrar en modo terminal.
* Desde la línea de comandos borramos el archivo `.AppleSetupDone` para que OS X ejecute la pantalla de bienvenida la próxima vez que reiniciemos:

        mount -uw /
        rm /var/db/.AppleSetupDone
        shutdown -h now

* Finalmente sólo hay que encender de nuevo el Mac y crear una cuenta.
