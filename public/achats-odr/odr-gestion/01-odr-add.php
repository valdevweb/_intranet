<?php
$eanFilename="";
$odrFilenames=[];
if(empty($_POST['date_start']) || empty($_POST['date_end']) || empty($_POST['gt']) || empty($_POST['famille']) || empty($_POST['marque'])){
	$errors[]="Merci de remplir tout les champs";
}
if(isset($_FILES['ean_file']['tmp_name']) && !empty($_FILES['ean_file']['tmp_name']) && !empty($_POST['ean'])){
	$errors[]="Vous devez choisir entre la saisie d'EAN et l'upload de fichier EAN ";
}
if(!is_numeric($_POST['gt'])){
	$errors[]="Veuillez saisir le numéro de GT";
}
if(empty($errors)){
	if(isset($_FILES['ean_file']['tmp_name']) && !empty($_FILES['ean_file']['tmp_name'])){
		$orginalFilename=$_FILES['ean_file']['name'];
		$ext = pathinfo($orginalFilename, PATHINFO_EXTENSION);

			// $filenameNoExt = basename($orginalFilename, '.'.$ext);
		$eanFilename = 'liste_des_ean_' . time() . '.' . $ext;
		$uploaded=move_uploaded_file($_FILES['ean_file']['tmp_name'],DIR_UPLOAD.'odr\\'.$eanFilename );
		if($uploaded==false){
			$errors[]="Nous avons rencontré un problème avec votre fichier, impossible de l'uploader vers le serveur";
		}else{
		}
	}
}
if(empty($errors)){
	if(isset($_FILES['odr_files']['tmp_name'][0]) && !empty($_FILES['odr_files']['tmp_name'][0])){

		for ($i=0; $i <count($_FILES['odr_files']['tmp_name']) ; $i++) {
			$orginalFilename=$_FILES['odr_files']['name'][$i];
			$ext = pathinfo($orginalFilename, PATHINFO_EXTENSION);

			$filenameNoExt = basename($orginalFilename, '.'.$ext);
			$filename = str_replace(' ', '_', $filenameNoExt) . '_' . time() . '.' . $ext;

			$uploaded=move_uploaded_file($_FILES['odr_files']['tmp_name'][$i],DIR_UPLOAD.'odr\\'.$filename );
			if($uploaded==false){
				$errors[]="Nous avons rencontré avec votre fichier, impossible de l'uploader vers le serveur";
			}else{
				$odrFilenames[]=$filename;
			}
		}
	}
}

if(empty($errors)){
	$idOdr=$odrDao->addOdr();
	if(!empty($_POST['ean'])){
		$listEan=explode(', ',$_POST['ean']);
		for ($i=0; $i < count($listEan); $i++) {

			$do=$odrDao->addEan($idOdr,$listEan[$i]);
		}
	}
	if(!empty($idOdr)){
		if(!empty($eanFilename)){
			$do=$odrDao->addEanFile($eanFilename,$idOdr);

		}
		if(!empty($odrFilenames)){
			for ($i=0; $i <count($odrFilenames) ; $i++) {
				$do=$odrDao->addOdrFileWithName($idOdr, $odrFilenames[$i], $_POST['filename'][$i], $_POST['ordre'][$i]);
			}

		}
	}
}
$successQ='?success';
unset($_POST);
header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);

