<?php
// $nbArticle=count($_POST['form_id']);
//comme n eput avoir des trou dans la numérotation même sur le form_id vu que l'on affiche pas les tête de box,
//on récupère la liste des index de $_POST['form_id'] et on boucle grâce à cette liste d'index
//
foreach ($_POST['form_id'] as $postKey => $idDetail) {
	$allfilename="";
	if(in_array($_POST['form_motif'][$postKey],$idReclamPhoto)){
		if(empty($_FILES['form_file']['name'][$postKey][0])){

			$errors[]="Vous avez sélectionné un ou des types de réclamations qui nécessitent l'ajout de photos. Merci d'ajouter des photos sur les articles concernés pour finaliser votre demande de litige";
		}

	}
}


foreach ($_POST['form_id'] as $postKey => $idDetail) {


	if(empty($errors)){
		if(isset($_FILES['form_file']['name'][$postKey][0]) && !empty($_FILES['form_file']['name'][$postKey][0])){

			$nbFiles=count($_FILES['form_file']['name'][$postKey]);
			for ($f=0; $f <$nbFiles ; $f++){
				$filename=$_FILES['form_file']['name'][$postKey][$f];
				echo $filename;
				$maxFileSize = 5 * 1024 * 1024; //5MB

				if($_FILES['form_file']['size'][$postKey][$f] > $maxFileSize){
					$errors[] = 'Attention un des fichiers dépasse la taille autorisée de 5 Mo';
				}
				else{
					// cryptage nom fichier
			 		// Get the fileextension
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
    				  // Get filename without extesion
					$filename_without_ext = basename($filename, '.'.$ext);
  					// Generate new filename => ajout d'un timestamp au nom du fichier
					$filename = str_replace(' ', '_', $filename_without_ext) . '_' . time() . '.' . $ext;
					$uploaded=move_uploaded_file($_FILES['form_file']['tmp_name'][$postKey][$f],$uploadDir.$filename );
				}
				if($uploaded==false){
					$errors[]="impossible de télécharger le fichier";
				}
				else{
					$allfilename.=$filename .';';
				}
			}

		}
	}


	if(empty($errors)){
		//pas d'inversion de ref
		if(!isset($_POST['radio-inv'][$postKey]) || $_POST['radio-inv'][$postKey]!=1){
			if($fLitige[$postKey]['occasion']==1){
				$valoLig=$fLitige[$postKey]['tarif']*$_POST['form_qte'][$postKey];
			}else{
				$valoLig=($fLitige[$postKey]['tarif']/$fLitige[$postKey]['qte_cde'])*$_POST['form_qte'][$postKey];
			}

			if($_POST['form_motif'][$postKey]==6)	{
				$valoLig= -$valoLig;
			}
			$ean="";
			$do=updateDetail($pdoLitige,$_POST['form_motif'][$postKey], $_POST['form_qte'][$postKey],$_POST['form_id'][$postKey],$allfilename,$ean, $valoLig);
		}else{
			// inversion de ref
			if(empty($_POST['qte_inv'][$postKey]) && empty($_POST['ean_inv'][$postKey])){
				$errors[]='merci de renseigner l\'EAN reçu et la quantité';
			}else{
				$prodFound=getProdInversion($pdoQlik,$_POST['ean_inv'][$postKey]);
				$ean=$_POST['ean_inv'][$postKey];
				// si prod en excédent trouve, on l'ajoute (nvelle ligne detail)
				if(count($prodFound)>=1){
					$valoLig=$prodFound[0]['pfnp']*$_POST['qte_inv'][$postKey];
					$valoLig=-1*$valoLig;
					$litigeDao->addDetailsExcedent($fLitige[0]['id'], $fLitige[0]['dossier'], $prodFound[0]['article'], $_POST['ean_inv'][$postKey], $prodFound[0]['dossier'], $prodFound[0]['libelle'], 0, $prodFound[0]['pfnp'], $prodFound[0]['fournisseur'], $prodFound[0]['cnuf'], $_POST['qte_inv'][$postKey],6, $valoLig);
					// pas besoin d'inserer lean dans inversion étatn donné que l'on a trouvé le produit
					$ean="";
				}
			// dans tous les cas, on met à jour  l'article detail
			// simplement on ne met l'ean dans le champ inversion que si on n'a pas trouvé le produit
				$valoLig=($fLitige[$postKey]['tarif']/$fLitige[$postKey]['qte_cde'])*$_POST['form_qte'][$postKey];
				$do=updateDetail($pdoLitige,$_POST['form_motif'][$postKey], $_POST['form_qte'][$postKey],$_POST['form_id'][$postKey],$allfilename,$ean, $valoLig);
			}
		}

	}
	$addCom=addDial($pdoLitige);
	if(empty($errors)){
		header('Location:declaration-validation.php?id='.$_GET['id']);
	}

}



