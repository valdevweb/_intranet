<?php 

			// envoi mail
		$infoMag=UserHelpers::getMagInfoByIdWebUser($pdoUser, $pdoMag, $_SESSION['id_web_user']);

		if(VERSION=="_"){
			$dest=[MYMAIL];
			$cc=[];
			$hidden=[];
		}else{
			$req=$pdoMag->prepare("SELECT * from lotus_ld WHERE galec= :galec AND ld_suffixe='-GT13'");
			$req->execute([
				':galec'	=>$infoMag['galec']
			]);
			while ($emailMag = $req->fetch()){
				if(isset($emailMag['email']) && filter_var($emailMag['email'], FILTER_VALIDATE_EMAIL)){
					$dest[]=$emailMag['email'];
				}
			}

			$cc=LD_OCCASION;
			$hidden=[MYMAIL];
		}
		// on ajoutera un message dans le mail si aucune adresse mail GT13 n'est trouvée
		$warning="";
		if(empty($dest)){
			$dest=[MYMAIL];
			$warning= "<p>Attention, le magasin n'a pas reçu ce mail de confirmation, aucune adresse mail n'a été trouvée dans les listes de diffusion GT Occasion</p>";
		}

		$pathXl=DIR_UPLOAD."excel\\";
		// D:\www\_intranet\upload\excel
		$filename=date('YmdHis').'.xlsx';
		// $filename=date('YmdHis').'-cde'.$lastinsertid.'-mag'.$infoMag['btlec_sca'].'.xlsx';

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A1', 'BTLec');
		$sheet->setCellValue('B1', 'Magasin');
		$sheet->setCellValue('C1', 'Palette');
		$sheet->setCellValue('D1', 'Article');
		$sheet->setCellValue('E1', 'Marque');
		$sheet->setCellValue('F1', 'Désignation');
		$sheet->setCellValue('G1', 'EAN');
		$sheet->setCellValue('H1', 'Quantité');
		$sheet->setCellValue('I1', 'Prix d\'achat');
		$sheet->setCellValue('J1', 'PVC');
		$row=2;

		$infoCde=$paletteDao->getCdeByIdCde($lastinsertid);

		foreach ($infoCde as $key => $cde)
		{
			if(!empty($cde['id_palette'])){
				$article=$cde['code_article'];
				$designation=$cde['designation'];
				$ean=$cde['ean'];
				$qte=$cde['quantite'];
				$palette=$arrayListPalette[$cde['id_palette']];
				$marque="";
				$ppi=$cde['pvc'];
			}else{
				$article=$cde['article_occ'];
				$designation=$cde['design_occ'];
				$ean=$cde['ean_occ'];
				$qte=$cde['qte_cde'];
				$palette="";
				$marque=$cde['marque_occ'];
				$ppi=$cde['ppi_occ'];


			}

			$sheet->setCellValue('A'.$row, $infoMag['btlec_sca']);
			$sheet->setCellValue('B'.$row, $infoMag['deno']);
			$sheet->setCellValue('C'.$row, $palette );
			$sheet->setCellValue('D'.$row, $article);
			$sheet->setCellValue('E'.$row, $marque);
			$sheet->setCellValue('F'.$row, $designation);
			$sheet->setCellValue('G'.$row, $ean);
			$sheet->setCellValue('H'.$row, $qte);
			$sheet->setCellValue('I'.$row, ($cde['pa']!=null)?$cde['pa']:'');
			$sheet->setCellValue('J'.$row, $ppi);
			$sheet->getStyle('G'.$row)
			->getNumberFormat()
			->setFormatCode(
				'0000000000000'
			);
			$row++;
		}
		$cols=['A','B','C','D','E','F','G'];
		for ($i=0; $i < sizeof($cols) ; $i++)
		{
			$sheet->getColumnDimension($cols[$i])->setAutoSize(true);

		}


		$sheet->setTitle('commande Leclerc Occasion');



		$writer = new Xlsx($spreadsheet);
		$writer->save($pathXl.$filename);

		// génération du pdf
		$totalPa=0;
		$totalQte=0;


		// génération du pdf
		$mpdf = new \Mpdf\Mpdf();
		ob_start();
		include('pdf-cmd-mag.php');
		$html=ob_get_contents();
		ob_end_clean();


		$mpdf->WriteHTML($html);
		// $mpdf->Output('test.pdf',\Mpdf\Output\Destination::DOWNLOAD);
		$pdfName='BL - cde Leclerc Occasion n.'.$lastinsertid .'.pdf';


		$pdfContent = $mpdf->Output('', 'S');



// ---------------------------------------
		$htmlMail = file_get_contents('mail-cmd-mag.html');
		$htmlMail=str_replace('{MAG}',$infoMag['deno'],$htmlMail);
		$htmlMail=str_replace('{WARNING}',$warning,$htmlMail);
		$subject='Portail BTLec - Leclerc Occasion - commande du magasin '. $infoMag['deno'];

// ---------------------------------------
		$transport = (new Swift_SmtpTransport(SMTP_ADDRESS, 25));
		$mailer = new Swift_Mailer($transport);
		$attachmentPdf = new Swift_Attachment($pdfContent, $pdfName, 'application/pdf');

		$message = (new Swift_Message($subject))
		->setBody($htmlMail, 'text/html')
		->setFrom(EMAIL_NEPASREPONDRE)
		->setTo($dest)
		->setCc($cc)
		->setBcc($hidden)
		->attach($attachmentPdf)
		->attach(Swift_Attachment::fromPath($pathXl.$filename));


		if (!$mailer->send($message, $failures)){
			print_r($failures);
		}else{
			$successQ='?success=cdeok';
			unset($_POST);
			header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
		}
	}
