<?php

function getUserChoice($pdoBt){
	$req=$pdoBt->prepare("SELECT * FROM salon_user_choice WHERE id_web_user= :id_web_user");
	$req->execute([
		':id_web_user'	=>$_SESSION['id_web_user']
	]);
	return $req->fetch();
}

function insertUserChoice($pdoBt){
	$req=$pdoBt->prepare("INSERT INTO salon_user_choice (id_web_user, mask, date_insert) VALUES (:id_web_user, :mask, :date_insert) ");
	$req->execute([
		':id_web_user'	=>$_SESSION['id_web_user'],
		':mask'	=>1,
		':date_insert'=>date("Y-m-d H:i:s")
	]);
}

// choice 0 => j'affiche le pop
// choice 1, je le masque
// par défaut, on masque => profil non mag et non bt n'ont pas besoin du pop
$choice=1;




if($_SESSION['type']=="mag"){
	$userChoice=getUserChoice($pdoBt);
	if(empty($userChoice)){
		$choice=0;
	}else{
		$choice=1;
	}
}
// traitement de la case à cocher du popup (ne plus afficher cette page
// on ajoute le choix de ne plus afficher en db et on recharge la page
// donc on revérifie si la personne a demandé de ne plus afficher
if(isset($_POST['input_choice'])){
	insertUserChoice($pdoBt);
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'],true,303);
}