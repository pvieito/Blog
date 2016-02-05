<?php
	$casta = ['Sí.', 'Definitivamente.', 'Por supuesto.', 'Sin duda.', 'La duda ofende.', 'Claro!', 'Y lo sabes.', 'Positivo.'];
?>
<head>
	<meta charset="UTF-8">
	<title>¿Eres Casta?</title>
</head>
<body>
	<center>
		<h2 style="margin-top:2em;">
			¿Eres Casta?™
		</h2>
		<h1 style="font-size:9em;">
			<?=$casta[rand(0, count($casta) - 1)]?>
		</h1>
	</center>
</body>