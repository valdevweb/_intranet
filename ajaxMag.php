<?php
require 'config/_pdo_connect.php';
//https://www.codexworld.com/dynamic-dependent-select-box-using-jquery-ajax-php/
// $_POST['centrale']="SCADIF";

if(!empty($_POST['centrale'])){
	// echo $_POST['centrale'];
	// $req=$pdoBt->query("SELECT * FROM sca3 WHERE centrale=" . $_POST['centrale'] ."");
	$req=$pdoBt->prepare("SELECT* FROM sca3 where centrale= :centrale AND mag NOT LIKE '*%' ORDER BY mag");
	$req->execute(array(
		'centrale' => $_POST['centrale']
	));

	// $rowCount=$req->rowCount();
	$results=$req->fetchAll(PDO::FETCH_ASSOC);
	if($results){
		foreach ($results as $mag)
		{
			echo '<option value="'.$mag['mag'].'">'. $mag['mag'] .'</option>';
		}
	}
	else
	{
		echo '<option value="">Aucun magasin</option>';
	}

}

?>