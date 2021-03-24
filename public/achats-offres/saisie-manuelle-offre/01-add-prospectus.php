<?php

$allreadyExist=$prospDao->getProspectusByProspectus(strtoupper($_POST['prospectus']));
if(!empty($allreadyExist)){
	$errors[]="Ce prospectus existe déjà, vous ne pouvez pas le recréer<br>Pour y ajouter des offres, veuillez le sélectionner dans la liste déroulante de la section saisie des offres";
}


if(empty($errors)){
	$otherFilename=[];
	if(isset($_FILES['fic-mod']['tmp_name']) && !empty($_FILES['fic-mod']['tmp_name'])){
		$uploaded=move_uploaded_file($_FILES['fic-mod']['tmp_name'],DIR_UPLOAD.'ficwopc\\'.$_FILES['fic-mod']['name'] );
		if($uploaded==false){
			$errors[]="Nous avons rencontré un problème avec votre fichier, la clôture n'a pas pu se faire";
		}
	}
	if(isset($_FILES['file_other']['tmp_name'][0]) && !empty($_FILES['file_other']['tmp_name'][0])){

		for ($i=0; $i <count($_FILES['file_other']['tmp_name']) ; $i++) {
			$orginalFilename=$_FILES['file_other']['name'][$i];
			$ext = pathinfo($orginalFilename, PATHINFO_EXTENSION);

			$filenameNoExt = basename($orginalFilename, '.'.$ext);
			$filename = str_replace(' ', '_', $filenameNoExt) . '_' . time() . '.' . $ext;

			$uploaded=move_uploaded_file($_FILES['file_other']['tmp_name'][$i],DIR_UPLOAD.'offres\\'.$filename );
			if($uploaded==false){
				$errors[]="Nous avons rencontré avec votre fichier, impossible de l'uploader vers le serveur";
			}else{
				$otherFilename[]=$filename;
			}
		}
	}
	if(empty($_POST['prospectus']) ||empty($_POST['date_start'])||empty($_POST['date_end'])){
		$errors[]="merci de remplir tous les champs";
	}
}

if(empty($errors)){

	$file=isset($_FILES['fic']['name'])?$_FILES['fic']['name']:"";
	$idProsp=$prospDao->addProspectus($_POST['date_start'],$_POST['date_end'],strtoupper($_POST['prospectus']),$file);

	if(!empty($otherFilename)){
		for ($i=0; $i < count($otherFilename) ; $i++) {
			$prospDao->insertFile($idProsp, $otherFilename[$i]);
		}
	}
	if(!empty($_POST['link'])){
		$arrayLink=explode(', ',$_POST['link']);
		if(!empty($arrayLink)){
			for ($i=0; $i < count($arrayLink) ; $i++) {
				$prospDao->insertLink($idProsp, $arrayLink[$i]);
			}
		}else{
			$prospDao->insertLink($_GET['prosp-id-mod'], $_POST['link']);
		}
	}

	$successQ='?success=prosp-add';
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);


}

