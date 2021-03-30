<?php
if(empty($_POST['main_cat']) || empty($_POST['cat']) || empty($_POST['date_start']) || empty($_POST['titre'])){
	$errors[]="Les champs non étoilés sont obligatoires, merci de vérifier qu'ils sont tous renseignés";
}
if(empty($errors)){

	if(isset($_FILES['gazette_files']['tmp_name'][0]) && !empty($_FILES['gazette_files']['tmp_name'][0])){
		for ($i=0; $i <count($_FILES['gazette_files']['tmp_name']) ; $i++) {
			$orginalFilename=$_FILES['gazette_files']['name'][$i];
			$ext = pathinfo($orginalFilename, PATHINFO_EXTENSION);

			$filenameNoExt = basename($orginalFilename, '.'.$ext);
			$filename = str_replace(' ', '_', $filenameNoExt) . '_' . time() . '.' . $ext;

			$uploaded=move_uploaded_file($_FILES['gazette_files']['tmp_name'][$i],DIR_UPLOAD.'gazette\\'.$filename );
			if($uploaded==false){
				$errors[]="Nous avons rencontré avec votre fichier, impossible de l'uploader vers le serveur";
			}else{
				$gazetteFilenames[]=$filename;
			}
		}
	}
}


if(empty($errors)){
	$gazetteDao->updateGazette($_GET['id']);
	if(isset($gazetteFilenames) && !empty($gazetteFilenames)){
		for ($i=0; $i <count($gazetteFilenames) ; $i++) {
			$gazetteDao->addFiles($_GET['id'], $gazetteFilenames[$i], $_POST['filename'][$i], $_POST['ordre'][$i]);
		}
	}
	if(!empty($_POST['link'])){
		$gazetteDao->deleteLinkByGazette($_GET['id']);

		$arrayLink=explode(', ',$_POST['link']);
		if(!empty($arrayLink)){
			for ($i=0; $i < count($arrayLink) ; $i++) {
				$gazetteDao->addLinks($_GET['id'], $arrayLink[$i]);
			}
		}else{
			$gazetteDao->addLinks($_GET['id'], $_POST['link']);
		}
	}
}
if(empty($errors)){
	$successQ='?id='.$_GET['id'].'&success=mod';
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
}