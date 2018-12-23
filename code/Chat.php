<?php
$pin = $_GET['pin'];

if (isset($_GET['pin']) && (int)$pin >= 100 && (int)$pin < 10000 && is_numeric($pin)) {

	$file = 'data/Chats/'.$pin.'.log';

	if(isset($_GET['user']) && isset($_GET['text'])) {

		$user = $_GET['user'];
		$text = html_entity_decode(trim($_GET['text']), ENT_NOQUOTES, 'UTF-8');

		if ($user != "" && $text != "") {
			$fopen = fopen($file, 'a+');
			$new = $new.date('Y-m-d:H:i:s')." - ".$user.": ".stripslashes($text)."\r\n";
			fwrite($fopen, $new);
			fclose($fopen);
		}
		else {
			header("HTTP/1.1 400 Bad Request");
			echo("Error: Mensaje o Usuario vacío\n");
			exit();
		}

	}
	echo(file_get_contents($file));
}
else {
	header("HTTP/1.1 401 Unauthorized");
	echo("Error: PIN Incorrecto\n");
	exit();
}
?>
