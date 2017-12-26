<?php

$services = array(
	0 => array(
		'id' => 15,
		'full_name' => 'green'
	),
	1=> array(
		'id' => 5,
		'full_name' => 'blue'
	),
	2=> array(
		'id' => 2,
		'full_name' => 'black'
	),
	3=> array(
		'4' => 2,
		'full_name' => 'yellow'
	)

);



$service=5;

$idtab = array_search(5, array_column($services, 'id'));
$service= $services[$idtab]['full_name']; // blue

echo $service;

// $lastHalf=array_slice($services,$key);
// 	echo "<pre>";
// 	var_dump($lastHalf);
// 	echo '</pre>';

// $firsthalf=array_slice($services,0, $key);











	echo "<pre>";
//	var_dump($firsthalf);
	echo '</pre>';

	$people = array(
		2 => array(
			'name' => 'John',
			'fav_color' => 'green'
		),
		5=> array(
			'name' => 'Samuel',
			'fav_color' => 'blue'
		),
		7=> array(
			'name' => 'val',
			'fav_color' => 'black'
		)
	);

	echo "<pre>";
	var_dump($people);
	echo '</pre>';


	$found_key = array_search('blue', array_column($people, 'fav_color')); // retour 1, l'emplacement de l'array dans le tableau
	$array_id = array_search($found_key,$people); // retour 1, l'emplacement de l'array dans le tableau
// echo 'id' . $array_id;

	$name= $people[$found_key];// le nom de l'array 5 =>samuel

echo $name;
	// $found_key = array_search($id, array_column($services, 'id'));
	// $colorName= $services[$found_key]['color'];
	// return $colorName;
	//
	//
	//recherche du service du user connecté dans l'array services
$found_key = array_search('blue', array_column($people, 'fav_color'));
//découpe le tableau à partir de la valeur recherchée jusqu'à la fin du tableau
$userService =array_slice($services,$found_key,1);