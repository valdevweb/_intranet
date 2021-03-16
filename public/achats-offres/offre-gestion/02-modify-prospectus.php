<?php
$ficFile="";
if(isset($_FILES['fic-mod']['tmp_name']) && !empty($_FILES['fic-mod']['tmp_name'])){

	$uploaded=move_uploaded_file($_FILES['fic-mod']['tmp_name'],DIR_UPLOAD.'ficwopc\\'.$_FILES['fic-mod']['name'] );
	if($uploaded==false){
		$errors[]="Nous avons rencontré un problème avec votre fichier, la clôture n'a pas pu se faire";
	}
}

if(empty($_POST['prospectus']) ||empty($_POST['date_start'])||empty($_POST['date_end'])){
	$errors[]="merci de remplir tous les champs";
}

if(empty($errors)){
	$done=$prospDao->updateProspectus($_GET['prosp-id-mod']);
	if($done==1){
		$successQ='?success=prosp-mod';
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
	}else{
		$errors[]="Une erreur s'est produite";
	}
}