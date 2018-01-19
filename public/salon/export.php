<?php
require('../../config/autoload.php');
require '../../functions/Csv.php';



$req=$pdoBt->prepare("SELECT id, objet,msg,date_msg,who,email FROM msg  ORDER BY id_service, date_msg DESC");
$req->execute();
$data=$req->fetchAll(PDO::FETCH_OBJ);
foreach ($data as $value) {
	//echo "\n" .'"'.$value->id .'";"' .$value->objet .'";"' .$value->date_msg .'";"' .$value->who .'";"' .$value->email.'";"';
	$datas[]=array(
		'id'=>$value->id,
		'objet'	=>$value->objet,
		'msg' =>$value->msg,
		'nom'	=>$value->who,
		'email'=>$value->email

	);
}



CSV::export($datas,"export");
