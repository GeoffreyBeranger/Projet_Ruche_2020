<?php

$file = './video/cam.jpg';
$today = date("Y-m-d H:i:s");
$name = 'Ruche_picture_' . $today . '.jpg';

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
 //   header('Content-Disposition: attachment; filename="'.basename($file).'"');
	header('Content-Disposition: attachment; filename="'.$name.'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
}





?>