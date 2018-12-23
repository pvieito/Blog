<?php
$opts = array(
	'http'=>array(
	'method' => "GET",
	'header' => "Cookie: remember_web_59ba36addc2b2f9401580f014c7f58ea4e30989d=eyJpdiI6ImNtakpNN290bEdramJsTXlOOTBIOFE9PSIsInZhbHVlIjoiVGh3NXFmVHpKRnR0QVFUSzRPclJ3TE53Tll3RjZNQkJaeW1cL1pYVTE5R1wvMmJvNHBwXC85QlNnUUNaN2VtVXkzTE1lTmJ6VGVMaGdkVlFqc3VwdGV2NFFod05iNW9MMUVlWnJubG8xSWJFdzg9IiwibWFjIjoiNmJlNzU5ZmMyZWQxNWM2YThlZmJjZDZmZTgwNTFiYjE2ODQxZDljYTdjNzZhOTA5NjJjOTA3YWY0Y2VjMWNlNiJ9; XSRF-TOKEN=eyJpdiI6Ikh4SEZhVU00SmcrMmU0VUhuWGVpckE9PSIsInZhbHVlIjoicHJhMURvVjBMeUhSblZ1SmRMN1lIdUtNak80dmFJZXBlZkVpY2FIMXpoVXErNzRcL0h6UW1adUMxWWhqbXpoUUpNbWdtSWxMWTdZVm9IU1MyNzI1cjdRPT0iLCJtYWMiOiJkZmM3MTBiODRjM2YxM2ZjZTUzOWM2YjJiNTFiYjc1Zjk3NDY3MDNmMDU4Y2Y0YjQwM2RmNTk5OWVjYWY3ZWVmIn0%3D; remember_82e5d2c56bdd0811318f0cf078b78bfc=eyJpdiI6InM4Y21aUVBWNThjY3AwWlBSVXVqTnc9PSIsInZhbHVlIjoibnF1ZFBXT3ozRmZmUzBrRDNUVVUrYnpvWVwvWUx2dzZmTSs2NHpBYXNMYWVScXd3NFpVK1wvdkI2WFpYbWhRM0lmdlh1aENEdStRT01MbFhEbU9SdDh4cElibVZ2aFErM1paYm5rTFNmbXc4ST0iLCJtYWMiOiI1NmY4M2NkMGUzMTk5ZTdkMTYyMmQ0NTdhODE1NDY3ODJjNmIzZWRmNjc3ZWIzNDI1NDhiOWY1NjNlNDgxMGNlIn0%3D; laravel_session=eyJpdiI6IjVpVFlQYzVQR2FCNmhyRTFuUldWY3c9PSIsInZhbHVlIjoiXC96YUJsWFwvRkZcL3phZThITWM1TzlNZG5iK1IwcnZ6TGorakxxTXBqT1JTRjVUcUMxanE5MFV3ZDJTVHVBVlRpVE1sS3NTUzVzQ3IxWFlRODJRTnV3cXc9PSIsIm1hYyI6ImIzYWMwYWNkNjQxMmQzZDZjZjg3NmQ4MTc3NGE5ZTA5YTA1M2FlNmQ4Y2Q5ODIzY2QxNTI4NTJhNGM1MmRiNTkifQ%3D%3D\r\n"
  )
);

date_default_timezone_set("Europe/Madrid");

$context = stream_context_create($opts);

$data = file_get_contents("http://new.showrss.info/schedule", false, $context);

libxml_use_internal_errors(true);
$dom = new DOMDocument;
$dom->loadHTML($data);
$xpath = new DOMXPath($dom);
$results = $xpath->query("//div[@class='col-md-10']/ul/li[1]");
$dates = $xpath->query("//div[@class='col-md-10']/strong");
$shows = $xpath->query("//div[@class='col-md-10']/ul[//strong]");

echo "BEGIN:VCALENDAR\r
METHOD:PUBLISH\r
VERSION:2.0\r
PRODID:-//PVIEITO//ShowRSS//EN\r\n";

if ($results->length > 0) {
	for ($i = 0; $i < $results->length; $i++) {
		$shows_list = $shows->item($i)->textContent;
		$shows_array = explode("\n\n\n", $shows_list);
		foreach ($shows_array as $show) {
			$show_ep = preg_split("/(?=\s[\d]+×)/", $show);
			echo "BEGIN:VEVENT\r\n";
			echo "UID:" . rand(1000, 10000000) . "@pvieito.com-ShowRSS\r\n";
			echo "SUMMARY:" . trim($show_ep[0]) . "\r\n";
			echo "DESCRIPTION:" . trim($show_ep[1]) . "\r\n";
			$date = $dates->item($i)->textContent;
			$start_date = strtotime($date);
			echo "DTSTART;VALUE=DATE:" . date("Ymd", $start_date) . "\r\n";
			$end_date = strtotime($date . ' +1 day');
			echo "DTEND;VALUE=DATE:" . date("Ymd", $end_date) . "\r\n";
			echo "END:VEVENT\r\n";
		}
	}
}

echo "END:VCALENDAR";
?>
