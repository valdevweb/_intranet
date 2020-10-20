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

		for ($i=0; $i <count($_POST['article_id']) ; $i++){
			// on récupère l'index de l'article coché en cherchant la valeur de $_POST['article_id'] (id statsventelitige) dans le table hidden_id. C'est les post ayant cet index que l'on devra récupérer et pousser danxs la db
			$rowArticle=array_search($_POST['article_id'][$i], $_POST['hidden_id']);
			if(!empty($_POST['hidden_boxhead'][$rowArticle])){
				$tete=1;
				$detailbox=NULL;
				$tetedebox=$_POST['hidden_article'][$rowArticle];
			}
			else{
				$tete=0;
				$detailbox=NULL;
			}


			if(empty($_POST['hidden_boxhead'][$rowArticle]) && !empty($_POST['hidden_boxdetail'][$rowArticle])){
				$detailbox=$_POST['hidden_article'][$rowArticle];
			}else{
				$detailbox=NULL;
			}

			$dateFact=date('Y-m-d H:i:s',strtotime($_POST['hidden_date_facture'][$rowArticle]));
			if(isset($_SESSION['vol-id'])){
				$poids=getPoids($pdoQlik,$_POST['hidden_article'][$rowArticle],$_POST['hidden_dossier'][$rowArticle]);
				if(count($poids==1)){
					$puv=$poids['puv'];
					$pul=$poids['pul'];
				}else{
					// $errors[]="ATTENTION, les poids n'ont pas pu être récupérés dans la base article";
					$puv=null;
					$pul=null;
				}
			}else{
				$puv=null;
				$pul=null;
			}


			$detail=addDetails($pdoLitige, $lastInsertId,$numDossier,$_POST['hidden_palette'][$rowArticle],$_POST['hidden_facture'][$rowArticle],$dateFact, $_POST['hidden_article'][$rowArticle], $_POST['hidden_ean'][$rowArticle],$_POST['hidden_dossier'][$rowArticle], $_POST['hidden_descr'][$rowArticle], $_POST['hidden_qte'][$rowArticle],$_POST['hidden_tarif'][$rowArticle], $_POST['hidden_fou'][$rowArticle], $_POST['hidden_cnuf'][$rowArticle],$tete,$detailbox,$puv,$pul );
			if($detail>0){
				$added++;
			}
			else{
				$errors[]="erreur à l'enregistrement";
			}
		}
		// suivant type de déclaration (palette complète ou non), on ne renvoie pas sur la même page
		if($added>0 && !isset($_POST['palette_complete'])){
			header('Location:declaration-steptwo.php?id='.$lastInsertId);
		}		elseif ($added>0 && isset($_POST['palette_complete'])){

			header('Location:declaration-steptwo-palette.php?id='.$lastInsertId);
		}
	}