---
title: Como eliminar Flash de OS X y ser feliz
lang: es
---

He decidido eliminar Flash Player de mi Mac y aquí os explico como lo he hecho. Recomiendo que lo probéis, desde que no tengo Flash instalado el rendimiento de OS X y sobretodo de Safari mejora mucho.

![Monitor de Procesos: Flash][1]

### ¿Por qué?

  * Es una tecnología que empeora, y mucho, el rendimiento de los ordenadores. La captura de ahí arriba indica su consumo de memoria (250 Mb) mientras no se estaba reproduciendo ningún vídeo, ¡ocupa más memoria RAM que el propio Safari!
  * Es la mayor fuente de _exploits_ de los navegadores modernos.
  * Está abocado a desaparecer y cuanto antes lo dejemos de usar mejor podrán evolucionar sus sustitutos: HTML5, CSS3,…

### Cómo se hace: ¡Adiós Flash!

![Internet Plug-Ins][2]

Para desactivar Flash hay que seguir estos pasos:

  1. Abrir la carpeta de los _Plug-Ins de Internet_: en el Finder, vamos al menú _Ir > Ir a la Carpeta…_, pegamos la dirección `/Library/Internet Plug-Ins/` en el cuadro y pulsamos _Ir_.
  2. Eliminamos los archivos “Flash Player.plugin” y “flashplayer.xpt”, que se encuentran en esa carpeta.

Tras cerrar y volver a abrir Safari, el contenido Flash ya habrá desaparecido. De esta manera Flash no se iniciará en ninguna página web de ningún navegador. O quizá sí, seguid leyendo.

### Flash con Google Chrome

Hace meses que YouTube y Vimeo usan reproductores de vídeo HTML cuando no está disponible Flash, tanto en las propias páginas como en los vídeos incrustados. Sin embargo, siempre es posible necesitar Flash.

Por suerte hay una solución sencilla, Google Chrome incluye su propio plugin de Flash integrado, que funciona independientemente a los plugins del sistema. Eliminar Flash Player de `/Library/Internet Plug-Ins/` evita que Safari o Firefox —y la inmensa mayoría de navegadores— carguen contenidos Flash, pero no Chrome.

Para cargar cualquier página que tengáis abierta en Safari en Chrome debéis tener activado el menú Desarrollo (hay una casilla de verificación en la pestaña “Avanzado” de las preferencias de Safari). El menú Desarrollo contiene un submenú llamado “Abrir página con”, que enumera todos los navegadores web instalados en el sistema, solo hay que hacer click en “Google Chrome” y ya está. Flash habrá vuelto.

   [1]: /media/2012/05/procesos-flash.png
   [2]: /media/2012/05/carpeta-plugins.png

