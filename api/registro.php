<?php
function format(&$simpleXmlObject, $filename){
	//Format XML to save indented tree rather than one line
	$dom = new DOMDocument('1.0');
	$dom->preserveWhiteSpace = false;
	$dom->formatOutput = true;
	$dom->loadXML($simpleXmlObject->asXML());
	return $dom->save($filename);
}
if (count($_POST)) {
	$file = 'storage/registro/Registros.xml';
	if (!file_exists($file)) {
		$xml = new SimpleXMLElement('<Registros/>');
	}
	else {
		$xml = simplexml_load_file($file);
	}
	$item = $xml->addChild('item');
	$item->addChild('timestamp', date('Y-m-d:H:i:s'));
	foreach ($_POST as $key => $value) {
		$item->addChild($key, $value);
	}
	format($xml, $file);

	header('HTTP/1.1 201 Created');
	echo('Created');
}
else if (count($_GET)) {
	$file = 'storage/registro/Registros.xml';
	$xml = simplexml_load_file($file);
	$xml_item = $_GET['item'];
?>
<table style="margin: auto; text-align: center;">
	<thead>
		<tr>
			<th>Time</th>
			<? foreach ($_GET as $key => $value) { ?>
		<th><? echo $key; ?></th>
		<? } ?>
		</tr>
	</thead>
	<tbody>

<?php foreach ($xml->item as $item) :?>
		<tr>
			<td><?php echo $item->timestamp; ?></td>
			<? foreach ($_GET as $key => $value) { ?>
		<td><?php echo $item->$key; ?></td>
		<? } ?>
		</tr>
<?php endforeach; ?>
	</tbody>
</table>
<?
}
else {
	header('HTTP/1.1 400 Bad Request');
	echo('Bad Request');
}
?>
