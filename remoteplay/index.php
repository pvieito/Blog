<?php
$file = 'stream.url';
if (isset($_GET['link'])) {
	echo("<meta http-equiv='Refresh' content='0;url=".file_get_contents($file)."'>");
	exit();
}
if (isset($_GET['url'])) {
	if (hash('sha256', $_GET['key']) == '3882830be8ded3280b6c755aa34827e6de62dca14ec91081f40f7e7eb3da26ad') {
		$url = htmlspecialchars($_GET['url']);
		$fopen = fopen($file, 'w+');
		fwrite($fopen, $url);
		fclose($fopen);
		echo("<meta http-equiv='Refresh' content='0;url=".$url."'>");
	}
	else {
		echo('ERROR');
		exit();
	}
}
else {
?>
<script type="text/javascript">
function detectKeys(e){
	var evtobj=window.event? event : e;
	if (evtobj.charCode == 127 || evtobj.charCode == 128 || evtobj.charCode == 13 || evtobj.charCode == 32) {
		if (player.paused) {
			document.getElementById("player").play();
		}
		else {
			document.getElementById("player").pause();
		}
	}
	if (evtobj.charCode == 27) {
		exitBrowser();
	}
}
document.onkeypress = detectKeys;
</script>
<body style="margin: 0; text-align: center; background-color: black;">
	<h1 id="test"></h1>
	<div style="margin: auto;">
		<video id="player" autoplay name="media">
			<source src="<?=file_get_contents($file);?>" type="video/mp4">
		</video>
	</div>
</body>
<?
}
?>