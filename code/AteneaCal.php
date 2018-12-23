<?php
$data = file_get_contents("http://atenea.upc.edu/moodle/calendar/export_execute.php?preset_what=all&preset_time=recentupcoming&userid=107997&authtoken=b1990f4ac31bf59817d0e1b5392609293b4cb1f6");

$data = preg_replace('/(DTSTART:)(\d{8})T\d{6}Z/i', 'DTSTART;VALUE=DATE:$2', $data);

echo $data;
?>
