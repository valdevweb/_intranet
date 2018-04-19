<?php

$uploadDir= '..\..\..\upload\documents\\';
$file='ODR_23.03.18.xls';
echo $uploadDir . $file;

if(unlink($uploadDir . $file)){
	echo "suppression ok";
}
else{
	echo "NOPE";
}


 ?>