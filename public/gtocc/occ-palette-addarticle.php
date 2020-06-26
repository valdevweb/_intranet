<?php

if(isset($_POST['add-article'])){
	// on vérifie que le magasin n'a pas ocmmandé un quantité supérieur à celle du stock
	if($_POST['qte_cde']>$_POST['qte_qlik']){
		$errors[]="Impossible d'ajouter la quantité, elle est supérieure au stock de l'article " .$_POST['article_qlik'];
	}
	if(empty($errors)){

		// on regarde si l'article est dans la table de commande temparaire
	// on fait un update sauf si quantité est à 0, là on supprime
		$inTemp=isMagArticleInTemp($pdoBt,$_POST['article_qlik']);
		if(empty($inTemp) && $_POST['qte_cde']!=0 ){
			$added=addToTempArt($pdoBt);
			$successQ='?success=article-add';
			unset($_POST);
			header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
		}elseif(!empty($inTemp) && $_POST['qte_cde']==0) {
		// on supprimer
			delLine($pdoBt,$inTemp['id']);
			unset($_POST);
			header("Location: ".$_SERVER['PHP_SELF'],true,303);

		}else{
		// on update
			updateTempArt($pdoBt,$inTemp['id']);
				$successQ='?success=mod';
			unset($_POST);
			header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
		}
	}

}