<?php 

// on créé le numéro de commande statut 2 = comandé comme pour les palettes
$req=$pdoOcc->query("INSERT INTO cdes_numero (statut) VALUES (2)");
$lastinsertid=$pdoOcc->lastInsertId();


foreach ($paletteEtArticleDansPanier as $key => $itemReserve) {

			// palette
	if(!empty($itemReserve['id_palette'])){

		$cdeOk=addToCmd($pdoOcc,$itemReserve['id_palette'],$lastinsertid, $itemReserve['article_occ'], $itemReserve['panf_occ'], $itemReserve['deee_occ'], $itemReserve['sorecop_occ'], $itemReserve['design_occ'], $itemReserve['fournisseur_occ'], $itemReserve['ean_occ'], $itemReserve['qte_cde'], $itemReserve['marque_occ'], $itemReserve['ppi_occ']);


		if($cdeOk){
			$statut=2;
			$upPalette=$paletteDao->updatePaletteStatut($pdoOcc,$itemReserve['id_palette'],$statut);
		}else{
			$errors[]="Une erreur est survenue avec la palette ".$itemReserve['palette'];
		}
		if($upPalette){
			$deleteTemRow=deleteTempCmd($pdoOcc,$itemReserve['id']);
		}

	}else{
				// article
		$cdeOk=addToCmd($pdoOcc,$itemReserve['id_palette'],$lastinsertid, $itemReserve['article_occ'], $itemReserve['panf_occ'], $itemReserve['deee_occ'], $itemReserve['sorecop_occ'], $itemReserve['design_occ'], $itemReserve['fournisseur_occ'], $itemReserve['ean_occ'], $itemReserve['qte_cde'], $itemReserve['marque_occ'], $itemReserve['ppi_occ']);


		if($cdeOk){
					// on supprime la ligne temporaire
			$deleteTemRow=deleteTempCmd($pdoOcc,$itemReserve['id']);
					// on met à jour les quantité de la table cde
					// donc on récupère la qte actuelle
			$qteStock=getQteArticleQlik($pdoOcc, $itemReserve['article_occ']);
			$qte=$qteStock - $itemReserve['qte_cde'];
			$ok=updateQteArticle($pdoOcc,$itemReserve['article_occ'], $qte);
			if(!$ok){
				$errors[]="une erreur est survenue, impossible de passer votre commande 1";
			}
		}else{
			$errors[]="une erreur est survenue, impossible de passer votre commande 2";
		}
	}
}
