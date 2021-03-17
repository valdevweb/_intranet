<?php
$ficFile="";
	if(isset($_FILES['fic']['tmp_name']) && !empty($_FILES['fic']['tmp_name'])){
		$uploaded=move_uploaded_file($_FILES['fic']['tmp_name'],DIR_UPLOAD.'ficwopc\\'.$_FILES['fic']['name'] );
		if($uploaded==false){
			$errors[]="Nous avons rencontré un problème avec votre fichier, la clôture n'a pas pu se faire";
		}
	}

	if(empty($_POST['prospectus']) ||empty($_POST['date_start'])||empty($_POST['date_end'])){
		$errors[]="merci de remplir tous les champs";
	}

	if(empty($errors)){
		$allreadyExist=$prospDao->getProspectusByProspectus(strtoupper($_POST['prospectus']));
		if(!empty($allreadyExist)){
			$errors[]="Ce prospectus existe déjà, vous ne pouvez pas le recréer<br> Pour ajouter des offres, veuillez aller dans la rubrique dédiée";
		}else{

			$file=isset($_FILES['fic']['name'])?$_FILES['fic']['name']:"";
			$done=$prospDao->addProspectus($_POST['date_start'],$_POST['date_end'],strtoupper($_POST['prospectus']),$file);
			if($done==1){
				$successQ='?success=prosp-add';
				unset($_POST);
				header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
			}else{
				$errors[]="Une erreur s'est produite";
			}
		}
	}

