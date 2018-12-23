<?php
$frases = [
	'Los amigos se conservan escuchando un poco más al corazón que a la cabeza.',
	'Los héroes son producto de su tiempo.',
	'El más poderoso es aquel que controla su propio poder.',
	'Confía en tus amigos y ellos tendrán razones para confiar en ti.',
	'Los grandes líderes inspiran la grandeza en el prójimo.',
	'Creer no es una elección, sino una convicción.',
	'El camino a la sabiduría es fácil para los que no están cegados por el ego.',
	'Un plan será tan bueno como aquellos que lo hayan ideado.',
	'La mejor fuente de confianza es la experiencia.',
	'El camino a la paz siempre valdrá la pena por más vueltas que de.',
	'Más vale fracasar con honor que vencer con engaños.',
	'La codicia y el miedo a la pérdida son las raíces del árbol de mal.',
	'Cuando a uno le rodea la guerra debe acabar escogiendo bando.',
	'La arrogancia merma la sabiduría.',
	'La verdad ilumina la mente, pero no siempre reconforta el corazón.',
	'El miedo es una enfermedad, la esperanza es su única cura.',
	'Una única oportunidad es una galaxia de esperanza.',
	'El camino a la cima de la grandeza es escarpado.',
	'Ignoramos lo que en verdad representa el coste de una guerra.',
	'El compromiso es una virtud a cultivar, no una debilidad a despreciar.',
	'Un secreto compartido puede fortalecer una confianza.',
	'Aprender una lección es sumarla a las ya sabidas.',
	'El exceso de confianza es el más peligroso de los descuidos.',
	'El primer paso para corregir un error es la paciencia.',
	'Nunca dudes de un corazón sincero.',
	'Debes creer en ti mismo o nadie lo hará.',
	'No hay regalo más valioso que la confianza.',
	'En ocasiones, es más difícil aceptar ayuda que ofrecerla.',
	'El apego no es compasión.',
	'Quien algo quiere, algo le cuesta.',
	'La búsqueda del honor es lo que hace a uno honorable.',
	'Lo fácil no siempre es sencillo.',
	'No tener en cuenta el pasado pone en peligro el futuro.',
	'No temas al futuro, no llores por el pasado.',
	'En la guerra, la primera víctima es la verdad.',
	'Buscar la felicidad es fácil, aceptarla es difícil.',
	'Un buen líder sabe cuado pasar a la acción.',
	'La valentía hace a los héroes, pero la confianza fomenta la amistad.',
	'La bestia más peligrosa es la que está en el interior.',
	'Quien fuese mi padre importa menos que el recuerdo que tengo de él.',
	'La adversidad es el mayor desafío para la amistad.',
	'Elige lo que es correcto, no lo que es fácil.',
	'Los hermanos de armas son hermanos para siempre.',
	'La guerra pone a prueba la destreza del soldado. Defender su hogar pone a prueba su corazón.',
	'Querer es poder.',
	'Un niño robado es una esperanza perdida.',
	'El reto de la esperanza es vencer la corrupción.',
	'Los que hacen cumplir la ley deben atenerse a ella.',
	'El futuro tiene mucho caminos: elige sabiamente.',
	'Fracasar en la elaboración de un plan es elaborar un plan para el fracaso.',
	'El amor puede tener cualquier forma y tamaño.',
	'El miedo es una excelente motivación.',
	'La verdad ahuyenta a los fantasmas del miedo.',
	'El camino más rápido hacia la destrucción es la venganza.',
	'Nadie nace malvado, sino que aprende a serlo.',
	'El camino del mal otorga un gran poder, pero ninguna lealtad.',
	'Aquel que afronta su culpabilidad demuestra equilibrio.',
	'Aquel que abandona la esperanza abandonará la vida.',
	'Aquel que ansíe controlar el destino no hallará jamás la paz.',
	'La adaptación es la clave de supervivencia.',
	'Todo lo que pueda salir mal, saldrá mal.',
	'Sin honor toda victoria está hueca.',
	'Sin humildad el valor es un juego peligroso.',
	'Todo maestro quiere ser un gran alumno.'
]
?>
<head>
	<meta charset="UTF-8">
	<title>The Clone Wars</title>
</head>
<body style="font-family: sans-serif;">
	<center>
		<h2 style="margin-top: 3em;">
			The Clone Wars
		</h2>
		<h1 style="font-size: 5em; margin: 0.5em;">
			<?=$frases[rand(0, count($frases) - 1)]?>
		</h1>
	</center>
</body>
