<?php
	$casta = ['Sí', 'Definitivamente', 'Por supuesto', 'Sin duda', 'La duda ofende', 'Claro!', 'Sí, y lo sabes', 'Positivo'];
?>
<head>
	<meta charset="UTF-8">
	<title>¿Eres Casta?</title>
</head>
<body style="font-family: sans-serif;">
	<center>
		<h2 style="margin-top: 3em;">
			¿Eres Casta?™
		</h2>
		<h1 style="font-size: 5em; margin: 0.5em;">
			<?=$casta[rand(0, count($casta) - 1)]?>
		</h1>
	</center>
</body>
