<?php
require('../../config/autoload.php');
require '../../functions/Csv.php';



$req=$pdoBt->prepare("SELECT id, id_galec,nom_mag,nom,prenom,fonction,date, entrepot,repas FROM salon  ORDER BY id_galec");
$req->execute();
$data=$req->fetchAll(PDO::FETCH_OBJ);
foreach ($data as $value) {
	//echo "\n" .'"'.$value->id .'";"' .$value->objet .'";"' .$value->date_msg .'";"' .$value->who .'";"' .$value->email.'";"';
	$datas[]=array(
		'id'=>$value->id,
		'id_galec'	=>$value->id_galec,
		'nom_mag' =>$value->nom_mag,
		'nom'	=>$value->nom,
		'prenom'=>$value->prenom,
		'fonction'=>$value->fonction,
		'date'=>$value->date,
		'entrepot'=>$value->entrepot,
		'repas'=>$value->repas


	);
}



CSV::export($datas,"export");
