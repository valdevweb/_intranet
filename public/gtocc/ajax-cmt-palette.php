<?php
include('../../config/autoload.php');
require '../../config/db-connect.php';



function updateCmt($pdoOcc){
	$req=$pdoOcc->prepare("UPDATE palettes SET cmt_palette= :cmt WHERE id = :id");
	$req->execute([
		':id'		=>$_POST['id'],
		':cmt'			=>$_POST['value'],

	]);
	if($req->rowCount()==1){
		echo "ok";
	}else{

	}
}





if($_SERVER['REQUEST_METHOD']=='POST'){

		$done=updateCmt($pdoOcc);

}

?>