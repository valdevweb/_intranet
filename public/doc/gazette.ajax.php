<?php
require('../../config/autoload.php');
require '../../config/db-connect.php';

if(!empty($_POST['id_doc_type']))
{
	$dir=URL_UPLOAD."gazette/";
	echo "<h5 class='text-center pb-3'>Derniers fichiers :</h5>";
	// entete tableau
	echo"<table class='table table-striped table-sm'>";
	echo"<thead><tr>";
	echo "<th>Du</th>";
	echo "<th>Au </th>";
	echo "<th>Fichier</th>";
	echo "<th>Descriptif</th>";
	echo "<th  class='text-center'>Supprimer</th></tr></thead><tbody>";

	// récupère les 10 dernière gazette du type de document sélectionné par le select de la page upload-main
	$req=$pdoBt->prepare("SELECT id,file, DATE_FORMAT(date,'%d-%m-%Y') as deb, DATE_FORMAT(date_fin,'%d-%m-%Y') as fin,title  FROM gazette WHERE id_doc_type= :id_doc_type ORDER BY id DESC LIMIT 10");
	$req->execute(array(
		':id_doc_type'	=>$_POST['id_doc_type']
	));

	$result=$req->fetchAll(PDO::FETCH_ASSOC);
	foreach ($result as $gazette)
	{
		echo "<tr><td>".$gazette['deb']."</td>";
		echo "<td>".$gazette['fin']. "</td>";
		echo "<td><a href='". $dir .$gazette['file'] ."'>".$gazette['file']. "</a></td>";
		echo "<td>".$gazette['title']. "</td>";
		echo "<td id='".$gazette['id']."' class='text-center'><a href='#' class='delete' onclick='deleteEl(".$gazette['id'].")'><i class='fas fa-trash-alt' id='".$gazette['id']."'></i></a></td></tr>";
	}

	// print_r($result);
	// echo $result[0]['file'];


	echo "</tbody></table>";

}



