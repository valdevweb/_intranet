<?php
require('../../config/autoload.php');
require '../../functions/Csv.php';



// $req=$pdoBt->prepare("SELECT id, id_galec,nom_mag,nom,prenom,fonction,date, entrepot,scapsav, repas FROM salon  ORDER BY id_galec");
$req=$pdoBt->prepare("SELECT id, id_galec,nom_mag,nom,prenom,fonction,date1, entrepot1,scapsav1, date2,entrepot2,scapsav2,repas2 FROM salon  ORDER BY id_galec");

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
		'date1'=>$value->date1,
		'entrepot1'=>$value->entrepot1,
		'scapsav1'=>$value->scapsav1,
		'date2'=>$value->date2,
		'entrepot2'=>$value->entrepot2,
		'scapsav2'=>$value->scapsav2,
		'repas2'=>$value->repas2
	);
}



CSV::export($datas,"export");
