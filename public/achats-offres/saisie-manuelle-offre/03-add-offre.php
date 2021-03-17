<?php


if(!is_numeric($_POST['pvc'])){
	$errors[]="Veuillez saisir un prix de vente conseillé correct (ex : 150, 10.52, etc.)";
}
if(!is_numeric($_POST['montant'])){
	$errors[]="Veuillez saisir un montant correct (ex : 150, 10.52, etc.)";
}
if(!is_numeric($_POST['montant_finance'])){
	$errors[]="Veuillez saisir un montant financé correct (ex : 150, 10.52, etc.)";
}

if(empty($_POST['ean']) || empty($_POST['marque']) || empty($_POST['reference']) || empty($_POST['gt'])){
	$errors[]="Merci de remplir tous les champs";
}
if(empty($_POST['id_prosp'])){
	$errors[]="Merci de sélectionner le prospectus sur lequel est l'offre";
}
if(empty($_POST['offre'])){
	$errors[]="Merci de sélectionner le type d'offre";
}
if(empty($errors)){
	$done=$offreDao->addOffre($_POST['id_prosp'], $_POST['gt'], strtoupper(trim($_POST['marque'])), $_POST['produit'], $_POST['reference'],$_POST['ean'], $pvc,$_POST['offre'], $_POST['montant'], $_POST['montant_finance'],"euro", "cmt" );
	if($done==1){
		$successQ='?success=add-offre#add-offre';
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
	}
}

