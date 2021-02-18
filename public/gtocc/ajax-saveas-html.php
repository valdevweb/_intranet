<?php
include('../../config/autoload.php');
require '../../config/db-connect.php';

if(isset($_POST['iframe'])){


	// $filenoext='info'.date('YmdHis');
	// $file = 'info'.date('YmdHis').'.html';
	// param $_GET['file'] utilisé pour lien prévisualisation
	$filenoext=$_POST['filename'];


	$file = $_POST['filename'].'.html';


	$target_dir = DIR_UPLOAD."flash\\";

	file_put_contents($target_dir.$file, $_POST['iframe']);
	function alreadyInDb($pdoOcc){
		$req=$pdoOcc->prepare("SELECT html_file FROM news WHERE html_file LIKE :html_file");
		$req->execute([
			':html_file'	=>$_POST['filename']
		]);
		$data=$req->fetch(PDO::FETCH_ASSOC);

		if(!empty($data)){
			return true;
		}
		return false;
	}
	if(!alreadyInDb($pdoOcc)){
		$req=$pdoOcc->prepare("INSERT INTO news (html_file, id_web_user, date_insert) VALUES (:html_file, :id_web_user, :date_insert)");
		$req->execute([
			':html_file'	=>$_POST['filename'],
			':id_web_user'	=>$_SESSION['id_web_user'],
			':date_insert'	=>date('Y-m-d H:i:s')
		]);
		$data=$req->errorInfo();
	}



	echo $filenoext;

}

?>