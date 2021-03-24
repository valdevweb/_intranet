<?php
$ficFile="";
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



if(empty($errors)){
	if(!empty($otherFilename)){
		for ($i=0; $i < count($otherFilename) ; $i++) {
			$prospDao->insertFile($_GET['prosp-id-mod'], $otherFilename[$i]);
		}
	}
	if(!empty($_POST['link'])){
		$arrayLink=explode(', ',$_POST['link']);
		if(!empty($arrayLink)){
			for ($i=0; $i < count($arrayLink) ; $i++) {
				$prospDao->insertLink($_GET['prosp-id-mod'], $arrayLink[$i]);
			}
		}else{
			$prospDao->insertLink($_GET['prosp-id-mod'], $_POST['link']);
		}
	}
	if(!empty($_FILES['fic-mod']['tmp_name'])){
		$done=$prospDao->updateProspectusWithFic($_GET['prosp-id-mod']);
	}else{
		$done=$prospDao->updateProspectusWithoutFic($_GET['prosp-id-mod']);
	}
	if($done==1){
		$successQ='?success=prosp-mod';
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
	}else{
		$errors[]="Une erreur s'est produite";
	}
}