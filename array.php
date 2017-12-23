<?php

$services=array('un','deux','trois','quatre','cinq','six','sept','huit');
$service='quatre';


$key=array_search($service, $services);
echo $key;

$lastHalf=array_slice($services,$key);
	echo "<pre>";
	var_dump($lastHalf);
	echo '</pre>';

$firsthalf=array_slice($services,0, $key);


	echo "<pre>";
	var_dump($firsthalf);
	echo '</pre>';

