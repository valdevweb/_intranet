<?php
require('../../config/autoload.php');

if(!empty($_POST['id_doc_type']))
{
	$dir="http://172.30.92.53/".$version."upload/gazette/";
	// entete tableau
	echo"<table class='table table-striped'>";
	echo"<thead><tr>";
	echo "<th scope='col'>date (début)</th>";
	echo "<th scope='col'>date (fin) </th>";
	echo "<th scope='col'>fichier</th>";
	echo "<th scope='col'>descriptif</th>";
	echo "<th scope='col'>supprimer</th></tr></thead><tbody>";

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
		echo "<td id='".$gazette['id']."'><a href='#' class='delete' onclick='deleteEl(".$gazette['id'].")'><i class='fa fa-trash fa-lg delete'   aria-hidden='true' id='".$gazette['id']."'></i></a></td></tr>";
	}

	// print_r($result);
	// echo $result[0]['file'];


	echo "</tbody></table>";

}



