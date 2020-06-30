<?php
function getQuantiteActuelle($pdoBt,$article){
	$req=$pdoBt->prepare("SELECT * FROM occ_article_qlik WHERE article_qlik= :article_qlik");
	$req->execute([
		':article_qlik'	=>$article

	]);
	return $req->fetch(PDO::FETCH_ASSOC);

}

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
if(isset($_POST['checkout'])){


	foreach ($paletteDansPanierMag as $key => $itemReserve) {
		// cas palette
		if(!empty($itemReserve['id_palette'])){
		//  on vérifie l'état des palettes si commandé ou expédié entre temps, on suppprile de temp et on averti le mag
			$paletteStatut = getPaletteStatut($pdoBt,$itemReserve['id_palette']);

			//  palette plus dispo
			if($paletteStatut['statut'] !=1){
				$errors[]="la palette ".$paletteStatut['palette'].' a été commandée entre temps par un autre magasin. Veuillez la supprimer';
			}
		}else{

			// on vérfie le stock article
			$quantiteActuelle = getQuantiteActuelle($pdoBt,$itemReserve['article_occ']);


			if($quantiteActuelle['qte_qlik']<$itemReserve['qte_cde']){
				$errors[]="le stock entrepôt pour l'article ".$itemReserve['article_occ']." n'est plus que de ".$quantiteActuelle['qte_qlik'].". Merci de modifier vos quantités";

			}

		}

	}
	// 	 sinon commande et met à jour
 // le statu de la palette en commandé
 // la table temporaire pourretirer toutes les palettes de cette commande
	if(empty($errors)){
			// on créé le numéro de commande statut 2 = comandé comme pour les palettes
		$req=$pdoBt->query("INSERT INTO occ_cdes_numero (statut) VALUES (2)");
		$lastinsertid=$pdoBt->lastInsertId();
		foreach ($paletteDansPanierMag as $key => $itemReserve) {

			// palette
			if(!empty($itemReserve['id_palette'])){

				$cdeOk=addToCmd($pdoBt,$itemReserve['id_palette'],$lastinsertid, $itemReserve['article_occ'], $itemReserve['panf_occ'], $itemReserve['deee_occ'], $itemReserve['sorecop_occ'], $itemReserve['design_occ'], $itemReserve['fournisseur_occ'], $itemReserve['ean_occ'], $itemReserve['qte_cde']);
				if($cdeOk){
					$statut=2;
					$upPalette=$paletteMgr->updatePaletteStatut($pdoBt,$itemReserve['id_palette'],$statut);
				}else{
					$errors[]="Une erreur est survenue avec la palette ".$itemReserve['palette'];
				}
				if($upPalette){
					$deleteTemRow=deleteTempCmd($pdoBt,$itemReserve['id']);
				}

			}else{
				// article
				$cdeOk=addToCmd($pdoBt,$itemReserve['id_palette'],$lastinsertid, $itemReserve['article_occ'], $itemReserve['panf_occ'], $itemReserve['deee_occ'], $itemReserve['sorecop_occ'], $itemReserve['design_occ'], $itemReserve['fournisseur_occ'], $itemReserve['ean_occ'], $itemReserve['qte_cde']);
				if($cdeOk){
					// on supprime la ligne temporaire
					$deleteTemRow=deleteTempCmd($pdoBt,$itemReserve['id']);
					// on met à jour les quantité de la table cde
					// donc on récupère la qte actuelle
					$qteStock=getQteArticleQlik($pdoBt, $itemReserve['article_occ']);
					$qte=$qteStock - $itemReserve['qte_cde'];
					$ok=updateQteArticle($pdoBt,$itemReserve['article_occ'], $qte);
					if(!$ok){
						$errors[]="une erreur est survenue, impossible de passer votre commande";
					}
				}else{
					$errors[]="une erreur est survenue, impossible de passer votre commande";
				}
			}
		}



	}

	if(empty($errors)){
			// envoi mail
		$infoMag=UserHelpers::getMagInfoByIdWebUser($pdoUser, $pdoMag, $_SESSION['id_web_user']);

		if(VERSION=="_"){
			$dest=['valerie.montusclat@btlec.fr'];
			$cc=[];
			$hidden=[];
		}else{
			$req=$pdoMag->prepare("SELECT * from email_gtoccasion WHERE id_web_user= :id_web_user");
			$req->execute([
				':id_web_user'	=>$infoMag['id_web_user']
			]);
			while ($emailMag = $req->fetch()){
				$dest[]=$emailMag['email'];
			}

			$cc=['jonathan.domange@btlec.fr', 'stephane.wendling@btlec.fr', 'luc.muller@btlec.fr'];
			$hidden=['valerie.montusclat@btlec.fr'];
		}

		$pathXl="D:\\www\\_intranet\\upload\\excel\\";
		// D:\www\_intranet\upload\excel
		$filename=date('YmdHis').'.xlsx';
		// $filename=date('YmdHis').'-cde'.$lastinsertid.'-mag'.$infoMag['btlec_sca'].'.xlsx';

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A1', 'BTLec');
		$sheet->setCellValue('B1', 'Magasin');
		$sheet->setCellValue('C1', 'Palette');
		$sheet->setCellValue('D1', 'Article');
		$sheet->setCellValue('E1', 'Désignation');
		$sheet->setCellValue('F1', 'EAN');
		$sheet->setCellValue('G1', 'Quantité');
		$sheet->setCellValue('H1', 'Prix d\'achat');
		$sheet->setCellValue('I1', 'PVC');
		$row=2;

		$infoCde=getFullCde($pdoBt,$lastinsertid);

		foreach ($infoCde as $key => $cde)
		{
			if(!empty($cde['id_palette'])){
				$article=$cde['code_article'];
				$designation=$cde['designation'];
				$ean=$cde['ean'];
				$qte=$cde['quantite'];
				$palette=$arrayListPalette[$cde['id_palette']];
			}else{
				$article=$cde['article_occ'];
				$designation=$cde['design_occ'];
				$ean=$cde['ean_occ'];
				$qte=$cde['qte_cde'];
				$palette="";

			}

			$sheet->setCellValue('A'.$row, $infoMag['btlec_sca']);
			$sheet->setCellValue('B'.$row, $infoMag['deno']);
			$sheet->setCellValue('C'.$row, $palette );
			$sheet->setCellValue('D'.$row, $article);
			$sheet->setCellValue('E'.$row, $designation);
			$sheet->setCellValue('F'.$row, $ean);
			$sheet->setCellValue('G'.$row, $qte);
			$sheet->setCellValue('H'.$row, ($cde['pa']!=null)?$cde['pa']:'');
			$sheet->setCellValue('I'.$row, ($cde['pvc']!=null)?$cde['pvc']:'');
			$sheet->getStyle('F'.$row)
			->getNumberFormat()
			->setFormatCode(
				'0000000000000'
			);
			$row++;
		}
// dimensionnement des colonnes
		$cols=['A','B','C','D','E','F','G'];
		for ($i=0; $i < sizeof($cols) ; $i++)
		{
			$sheet->getColumnDimension($cols[$i])->setAutoSize(true);

		}


		$sheet->setTitle('commande Leclerc Occasion');



		$writer = new Xlsx($spreadsheet);
		$writer->save($pathXl.$filename);

// ---------------------------------------
		$htmlMail = file_get_contents('mail-cmd-mag.html');
		$htmlMail=str_replace('{MAG}',$infoMag['deno'],$htmlMail);
		$subject='Portail BTLec - Leclerc Occasion - commande du magasin '. $infoMag['deno'];

// ---------------------------------------
		$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
		$mailer = new Swift_Mailer($transport);
		$message = (new Swift_Message($subject))
		->setBody($htmlMail, 'text/html')
		->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec Est'))
		->setTo($dest)
		->setCc($cc)
		->setBcc($hidden)
		->attach(Swift_Attachment::fromPath($pathXl.$filename));


		if (!$mailer->send($message, $failures)){
			print_r($failures);
		}else{
			$successQ='?success=cdeok';
			unset($_POST);
			header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
		}



			// header("Location:occ-palette.php?success=cde");
	}

}

