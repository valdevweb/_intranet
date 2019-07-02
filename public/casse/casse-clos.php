<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}
function addFacDate($pdoCasse){
	$req=$pdoCasse->prepare("UPDATE palettes SET date_fac= :date_fac WHERE id_exp= :id");
	$req->execute([
		':id'	=>$_POST['id'],
		':date_fac'=>date('Y-m-d H:i:s')
	]);
	return $req->rowCount();
}
function closeCasse($pdoCasse){
	$req=$pdoCasse->prepare("UPDATE casses LEFT JOIN palettes ON casses.id_palette=palettes.id LEFT JOIN exps ON palettes.id_exp= exps.id SET etat=1  WHERE exps.id= :id");
	$req->execute([
		':id'	=>$_POST['id']
	]);
	return $req->rowCount();
}


$added=addFacDate($pdoCasse);
$close=closeCasse($pdoCasse);
if($added>0 && $close >0){
	echo '<div class="alert alert-success" role="alert">La date de facturation a été ajoutée et les casses ont été clôturées</div>';
}
else{
        echo '<div class="alert alert-primary" role="alert">Une erreur est survenue</div>';
}