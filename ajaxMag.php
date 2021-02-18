<?php
require 'config/config.inc.php';
require 'config/db-connect.php';

//https://www.codexworld.com/dynamic-dependent-select-box-using-jquery-ajax-php/
// $_POST['centrale']="SCADIF";

if(!empty($_POST['centrale'])){
	// echo $_POST['centrale'];
	// $req=$pdoBt->query("SELECT * FROM sca3 WHERE centrale=" . $_POST['centrale'] ."");
	// $req=$pdoBt->prepare("SELECT* FROM sca3 where centrale= :centrale AND mag NOT LIKE '*%' ORDER BY mag");
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



