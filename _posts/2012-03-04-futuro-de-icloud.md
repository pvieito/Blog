---
layout: post
title: Sobre el futuro de iCloud y “Documentos en la nube”
---

Desde la presentación de iCloud por Steve Jobs en la pasada WWDC una de las características que desgraciadamente han tenido menos éxito ha sido “Documentos en la nube”. Esta característica prácticamente no ha sido implementada en aplicaciones de terceros y sin embargo su potencial es enorme.

![Todos mis Documentos][1]

A mi parecer hay dos problemas con “Documentos en la nube” que Apple debería solucionar:

  * La Arquitectura de “Documentos en la nube” es incoherente. Sigue el principio de cada aplicación maneja su contenido, lo cual en principio parece correcto pero al final es un gran estorbo. Para mi lo que deberían hacer es dividir el contenido en Tipos o Clases, como ya hace ahora OS X en la carpeta Todos mis archivos: Documentos, Hojas de Cálculo, Películas, Imágenes,… Así las diferentes aplicaciones de edición de texto tendrían acceso a Documentos, las de edición de vídeo a Películas.
  * Aún cuando una aplicación sea para hacer presentaciones —Keynote, por ejemplo— ésta debe tener acceso a otros archivos como Documentos, Imágenes, Documentos PDF,…

La Arquitectura actual no permite esto y “Documentos en la nube” no funcionará hasta que se implementen estas mejoras u otras incluso más profundas.

   [1]: /media/2012/03/documents-in-the-cloud.png
