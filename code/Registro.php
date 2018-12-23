<?php
function format(&$simpleXmlObject, $filename){
	//Format XML to save indented tree rather than one line
	$dom = new DOMDocument('1.0');
	$dom->preserveWhiteSpace = false;
	$dom->formatOutput = true;
	$dom->loadXML($simpleXmlObject->asXML());
	return $dom->save($filename);
}

$file = 'data/Registro/Registros.xml';

if (count($_POST)) {
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
	echo('Created\r\n');
}
else if (count($_GET)) {
	$xml = simplexml_load_file($file);
	$xml_item = $_GET['item'];
?>
<table style="margin: auto; text-align: center;">
	<thead>
		<tr>
			<th>Time</th>
			<?php foreach ($_GET as $key => $value) { ?>
				<th><?php echo $key; ?></th>
			<?php } ?>
		</tr>
	</thead>
	<tbody>

	<?php foreach ($xml->item as $item) :?>
		<tr>
			<td><?php echo $item->timestamp; ?></td>
			<?php foreach ($_GET as $key => $value) { ?>
				<td><?php echo $item->$key; ?></td>
			<?php } ?>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<?php
}
else {
	header('HTTP/1.1 400 Bad Request');
	echo("Bad Request");
}
?>
