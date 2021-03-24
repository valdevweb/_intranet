<?php

if(is_numeric($_POST['montant'])){
	$montant =number_format( $_POST['montant'] ,2 , "." ," " );

}else{
	$errors[]="Veuillez saisir un montant correct (ex : 150, 10.52, etc.)";
}
if(is_numeric($_POST['montant_finance'])){
	$montantF =number_format( $_POST['montant_finance'] ,2 , "." ," " );

}else{
	$errors[]="Veuillez saisir un montant correct (ex : 150, 10.52, etc.)";
}
if(is_numeric($_POST['pvc'])){
	$pvc =number_format( $_POST['pvc'] ,2 , "." ," " );

}else{
	$errors[]="Veuillez saisir un prix de vente conseillé correct (ex : 150, 10.52, etc.)";
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
	$done=$offreDao->updateOffre($_GET['offre-modif'], $montant, $montantF, $pvc);
	if($done==1){
		$successQ='?success=update-offre#offre-'.$_GET['offre-modif'];
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
	}
}

