<?php
include('../../config/autoload.php');
require '../../config/db-connect.php';

function alreadyCmt($pdoOcc){
	$req=$pdoOcc->prepare("SELECT * FROM articles_qlik_cmt WHERE article= :article");
	$req->execute([
		':article'		=>$_POST['id']
	]);
	if(!empty($req->fetch())){
		return true;
	}

	return false;
}


function updateCmt($pdoOcc){
	$req=$pdoOcc->prepare("UPDATE articles_qlik_cmt  SET cmt= :cmt, insert_by= :insert_by, insert_on= :insert_on WHERE article = :article");
	$req->execute([
		':article'		=>$_POST['id'],
		':cmt'			=>$_POST['value'],
		':insert_by'	=>$_SESSION['id_web_user'],
		':insert_on'	=>date('Y-m-d H:i:s')
	]);
	if($req->rowCount()==1){
		echo "ok";
	}else{
			echo "<pre>";
			print_r($req->errorInfo());
			echo '</pre>';

		echo "error";
	}
}


function insertCmt($pdoOcc){
	$req=$pdoOcc->prepare("INSERT INTO articles_qlik_cmt  (cmt, article, insert_by, insert_on) VALUES (:cmt, :article, :insert_by, :insert_on)");
	$req->execute([
		':article'		=>$_POST['id'],
		':cmt'			=>$_POST['value'],
		':insert_by'	=>$_SESSION['id_web_user'],
		':insert_on'	=>date('Y-m-d H:i:s')
	]);
	if($req->rowCount()==1){
		echo "ok";
	}else{
					echo "<pre>";
			print_r($req->errorInfo());
			echo '</pre>';
		echo "error";
	}
}

if($_SERVER['REQUEST_METHOD']=='POST'){
	$doUpdate=alreadyCmt($pdoOcc);
	if($doUpdate){
		updateCmt($pdoOcc);
	}else{
		insertCmt($pdoOcc);
	}


}

?>