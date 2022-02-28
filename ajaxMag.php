<?php
require 'config/config.inc.php';
require 'config/db-connect.php';


if(!empty($_POST['centrale'])){

	$req=$pdoMag->prepare("SELECT * FROM mag where centrale= :centrale  AND  gel!=9 ORDER BY deno ");
	$req->execute(array(
		'centrale' => $_POST['centrale']
	));

	// $rowCount=$req->rowCount();
	$results=$req->fetchAll(PDO::FETCH_ASSOC);
	if($results){
		foreach ($results as $mag)
		{
			echo '<option value="'.$mag['galec'].'">'. $mag['deno'] .'</option>';
		}
	}
	else
	{
		echo '<option value="">Aucun magasin</option>';
	}

}

?>



