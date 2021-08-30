<?php
// 1- creation du dossier
if(empty($_POST['article_id'])){
	$errors[]="Merci de sélectionner un article";
}
if(empty($errors)){
		//si on a une variable de session vol-id, il faut vérifier ce qu'elle renvoie :
		//si elle est égale à zéro, c'est une nouvelle décalration de vol donc on la créé et on récupère son id,
		//sinon c'est la suite d'une décalartion existante donc on récupère la valeur de session['id_vol']
	if(isset($_SESSION['vol-id'])){
		if($_SESSION['vol-id']==0){
				//ajout nouveau vol dans la table robbery
			$idRobbery=insertRobbery($pdoLitige);
			if($idRobbery>=0){
			}
			else{
				$errors[]="impossible d'ajouter le vol";
			}
			$_SESSION['vol-id']=$idRobbery;
		}
		else{
			$idRobbery=$_SESSION['vol-id'];
		}
	}
	else{
		$idRobbery=null;
	}

		// si le numéro de dossier a été saisi, on le mémorise dans $_SESSION['dossier_litige']
	if(!empty($_POST['num_dossier_form'])){
		$_SESSION['dossier_litige']=$_POST['num_dossier_form'];
	}
		// le numéro de dossier sera ecrasé au moment de la recopie de la table temporaire vers la table active
	$numDossier=9999;
	$lastInsertId=insertDossier($pdoLitige,$numDossier, $magId, $idRobbery);

	// créa du dossier (sans numéro officiel pour l'instant)
	if($lastInsertId>0){
		$sucess[]="ajout du dossier réussie";
	}
	else{
		$errors[]="Impossible de créer le dossier";
	}
}

// 2- ajout des ref art, num, num fac, date fac, n°palette, qte originale, num dossier,gencod, id_web_user, btlec, galec, deno
if(count($errors)==0){
	$tete=0;
	$added=0;
	$dossierWithOcc=false;

	for ($i=0; $i <count($_POST['article_id']) ; $i++){
			// on récupère l'index de l'article coché en cherchant la valeur de $_POST['article_id'] (id statsventelitige) dans le table hidden_id. C'est les post ayant cet index que l'on devra récupérer et pousser danxs la db
		$rowArticle=array_search($_POST['article_id'][$i], $_POST['hidden_id']);

		$palette=$_POST['hidden_palette'][$rowArticle];
		$fac=$_POST['hidden_facture'][$rowArticle];
		$dateFact=date('Y-m-d H:i:s',strtotime($_POST['hidden_date_facture'][$rowArticle]));
		$ean=$_POST['hidden_ean'][$rowArticle];
		$descr=$_POST['hidden_descr'][$rowArticle];
		$qte=$_POST['hidden_qte'][$rowArticle];
		$tarif=$_POST['hidden_tarif'][$rowArticle];
		$fou=$_POST['hidden_fou'][$rowArticle];

		$art=empty($_POST['hidden_article'][$rowArticle])? NULL : $_POST['hidden_article'][$rowArticle];
		$dossier=empty($_POST['hidden_dossier'][$rowArticle]) ? NULL : $_POST['hidden_dossier'][$rowArticle];
		$cnuf=empty($_POST['hidden_cnuf'][$rowArticle]) ? NULL : $_POST['hidden_cnuf'][$rowArticle];
		$occArticlePalette=empty($_POST['hidden_occasion'][$rowArticle]) ? NULL : $_POST['hidden_occasion'][$rowArticle];

		if(!empty($_POST['hidden_occasion'][$rowArticle])){
			$dossierWithOcc=true;
		}
		$puv=null;
		$pul=null;
		if(!empty($_POST['hidden_boxhead'][$rowArticle])){
			$tete=1;
			$detailbox=NULL;
			$tetedebox=$_POST['hidden_article'][$rowArticle];
		}else{
			$tete=0;
			$detailbox=NULL;
		}

		if(empty($_POST['hidden_boxhead'][$rowArticle]) && !empty($_POST['hidden_boxdetail'][$rowArticle])){
			$detailbox=$tetedebox;
		}else{
			$detailbox=NULL;
		}

		if(isset($_SESSION['vol-id'])){
			if($art!=NULL && $dossier !=NULL){
				$poids=getPoids($pdoQlik,$_POST['hidden_article'][$rowArticle],$_POST['hidden_dossier'][$rowArticle]);
				if(count($poids==1)){
					$puv=$poids['puv'];
					$pul=$poids['pul'];
				}
			}
		}


		$idDetail=$litigeDao->addDetails($lastInsertId,$numDossier, $palette, $fac, $dateFact, $art, $ean,$dossier, $descr, $qte,$tarif, $fou, $cnuf,$tete,$detailbox,$occArticlePalette, $puv,$pul );
		// recup info gt dans ba pour chaque article
		$infoArt=$baDao->getArtDossier($art, $dossier);
		if(!empty($infoArt)){
			$litigeDao->updateDetailTemp($idDetail,$infoArt['gt']);
		}
	}
	//  si on a eu au moins un article occ dans la déclaration, on tag le dossier à occ=1
	if($dossierWithOcc){
		$litigeDao->updateOccDossier($lastInsertId);
	}


		// suivant type de déclaration (palette complète ou non), on ne renvoie pas sur la même page
	if(!isset($_POST['palette_complete'])){
		header('Location:declaration-steptwo.php?id='.$lastInsertId);
	}elseif ( isset($_POST['palette_complete'])){
		header('Location:declaration-steptwo-palette.php?id='.$lastInsertId);
	}else{
		$errors[]="nb added " . $added ." nb sel " .count($_POST['article_id']);
	}
}

// $added==count($_POST['article_id']) &&