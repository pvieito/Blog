---
layout: post
title: 'Cocoa: Como hacer que se abra una NSWindow mediante un NSMenuItem'
---

Esto que parece tan sencillo realmente lo es. Primero creamos en el .xib la `NSWindow` y desmarcamos “Release When Closed” y si fuese necesario también “Visible At Launch”. Una vez hecho esto podemos declarar la `NSWindow` y el método para abrirla en `AppDelegate.h`:

	IBOutlet NSWindow *nombreDeLaVentana;
	- (IBAction)abrirVentana: (id)sender;

Luego simplemente implementamos el método en `AppDelegate.m`:

	- (IBAction)abrirVentana: (id)sender {
		[nombreDeLaVentana makeKeyAndOrderFront:sender];
	}

Finalmente enlazamos los métodos y objetos en el archivo .xib y Voilà!
