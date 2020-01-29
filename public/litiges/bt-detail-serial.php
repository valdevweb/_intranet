<?php
require('../../config/autoload.php');

if(!empty($_POST['idprod'])){
	$req=$pdoLitige->prepare("SELECT serials FROM details WHERE id=:id");
	$req->execute([
		':id'		=>$_POST['idprod']

	]);
	$result=$req->fetch(PDO::FETCH_ASSOC);
	 echo $result['serials'];


}





