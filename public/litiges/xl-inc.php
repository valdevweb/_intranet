<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// ------------------------------------
// JULIE
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Dossier');
$sheet->setCellValue('B1', 'Date déclaration');
$sheet->setCellValue('C1', 'Magasin');
$sheet->setCellValue('D1', 'Code BT');
$sheet->setCellValue('E1', 'Galec');
$sheet->setCellValue('F1', 'Centrale');
$sheet->setCellValue('G1', 'Etat');
$sheet->setCellValue('H1', 'Date clôture');
$sheet->setCellValue('I1', '24/48h');
$sheet->setCellValue('J1', '24/48h ESP');
$sheet->setCellValue('K1', 'Soldé');
$sheet->setCellValue('L1', 'palette');
$sheet->setCellValue('M1', 'Palette Occasion');
$sheet->setCellValue('N1', 'date facture');
$sheet->setCellValue('O1', 'article');
$sheet->setCellValue('P1', 'ean');
$sheet->setCellValue('Q1', 'sn');
$sheet->setCellValue('R1', 'Désignation');
$sheet->setCellValue('S1', 'Fournisseur');
$sheet->setCellValue('T1', 'Quantité commandée');
$sheet->setCellValue('U1', 'Tarif');
$sheet->setCellValue('V1', 'Quantité litige');
$sheet->setCellValue('W1', 'Réclamation');
$sheet->setCellValue('X1', 'Article reçu');
$sheet->setCellValue('Y1', 'Quantité reçue');
$sheet->setCellValue('Z1', 'EAN / palette reçu');
$sheet->setCellValue('AA1', 'Tarif article reçu');
$sheet->setCellValue('AB1', 'Valo');
$sheet->setCellValue('AC1', 'Transporteur');
$sheet->setCellValue('AD1', 'Affreteur');
$sheet->setCellValue('AE1', 'Transit');
$sheet->setCellValue('AF1', 'Preparateur');
$sheet->setCellValue('AG1', 'Contrôleur');
$sheet->setCellValue('AH1', 'Chargeur');
$sheet->setCellValue('AI1', 'Réglement Transporteur');
$sheet->setCellValue('AJ1', 'Réglement assurance');
$sheet->setCellValue('AK1', 'Réglement fournisseur');
$sheet->setCellValue('AL1', 'Réglement magasin');
$sheet->setCellValue('AM1', 'Facture magasin');
$sheet->setCellValue('AN1', 'Typologie');
$sheet->setCellValue('AO1', 'Imputation');
$sheet->setCellValue('AP1', 'Analyse');
$sheet->setCellValue('AQ1', 'Réponse');












$styleArray = [
	'font' => [
		'bold' => true,
		'color'=>['rgb'=>'FFFFFF']
	],
	'borders' => [
		'top' => [
			'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
		],
	],
];
$styleArrayDossier = [
	'fill' => [
		'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
		'color' => [
			'rgb' => '0075BC',
		],
	],
];
$styleArrayDetail = [
	'fill' => [
		'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
		'color' => [
			'rgb' => '004570',
		],
	],
];
$styleArrayMontant = [
	'fill' => [
		'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
		'color' => [
			'rgb' => 'FF5666',
		],
	],
];
$styleArrayAutre = [
	'fill' => [
		'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
		'color' => [
			'rgb' => '38686A',
		],
	],
];
$styleArrayEquipe = [
	'fill' => [
		'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
		'color' => [
			'rgb' => 'A3B4A2',
		],
	],
];
$styleArrayAnalyse = [
	'fill' => [
		'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
		'color' => [
			'rgb' => 'FF1B31',
		],
	],
];

$spreadsheet->getActiveSheet()->getStyle('A1:AP1')->applyFromArray($styleArray);

$spreadsheet->getActiveSheet()->getStyle('A1:K1')->applyFromArray($styleArrayDossier);
$spreadsheet->getActiveSheet()->getStyle('L1:Z1')->applyFromArray($styleArrayDetail);
$spreadsheet->getActiveSheet()->getStyle('AA1:AC1')->applyFromArray($styleArrayAutre);
$spreadsheet->getActiveSheet()->getStyle('AD1:AF1')->applyFromArray($styleArrayEquipe);
$spreadsheet->getActiveSheet()->getStyle('AG1:AL1')->applyFromArray($styleArrayMontant);
$spreadsheet->getActiveSheet()->getStyle('AM1:AP1')->applyFromArray($styleArrayAnalyse);

$dossierW=0;

$row=2;
foreach ($listLitige as $key => $dossier){

	if($dossier['vingtquatre']==1){
		$vingtquatre='oui';
	}else{
		$vingtquatre='non';
	}

	if($dossier['esp']==1){
		$esp='oui';
	}else{
		$esp='non';
	}


	// si inversion de palette, il faut aller chercher dans la table palette_inv
	if($dossier['id_reclamation']==7){
		$valoLig= $dossier['valo_line'];
		if(!in_array($dossier['id_main'],$listDossierInvPalette)){
			$listDossierInvPalette[]=$dossier['id_main'];
		}
	}elseif($dossier['id_reclamation']==5){
		// ce qui a été commandé - ce qui a été reçu
		if($dossier['inv_tarif']==null){
			$valoLig=(($dossier['tarif']/$dossier['qte_cde'])*$dossier['qte_litige']);

		}else{
			$valoLig=(($dossier['tarif']/$dossier['qte_cde'])*$dossier['qte_litige'])-($dossier['inv_qte']*$dossier['inv_tarif']);
		}
		//
	}else{
		$valoLig= $dossier['valo_line'];

	}

	if($dossier['id_main']==$dossierW){
		$mtTransp=0;
		$mtAssur=0;
		$mtFourn=0;
		$mtMag=0;
	}else{

		$mtTransp=nullToZero($dossier['mt_transp']);
		$mtAssur=nullToZero($dossier['mt_assur']);
		$mtFourn=nullToZero($dossier['mt_fourn']);
		$mtMag=nullToZero($dossier['mt_mag']);
	}
	if(!empty($dossier['occ_article_palette'])){
			$article="Palette ".OccHelpers::getPaletteNameByArticlePalette($pdoOcc,$dossier['occ_article_palette']);
	}else{
		$article=$dossier['article'];
	}
// echo arrayToString($arAffrete,5);

	$solde=($dossier['etat_dossier']==0)?'non':'oui';
	$sheet->setCellValue('A'.$row, $dossier['dossier']);
	$sheet->setCellValue('B'.$row, date('d-m-Y', strtotime($dossier['date_crea'])));
	$sheet->setCellValue('C'.$row, $dossier['deno']);
	$sheet->setCellValue('D'.$row, $dossier['btlec']);
	$sheet->setCellValue('E'.$row, $dossier['galec']);
	$sheet->setCellValue('F'.$row, arrayToString($arCentrale, $dossier['centrale']));
	$sheet->setCellValue('G'.$row, arrayToString($arEtat,$dossier['id_etat']));
	$sheet->setCellValue('H'.$row, date('d-m-Y', strtotime($dossier['date_cloture'])));
	$sheet->setCellValue('I'.$row, $vingtquatre);
	$sheet->setCellValue('J'.$row, $esp);
	$sheet->setCellValue('K'.$row, $solde);
	$sheet->setCellValue('L'.$row, $dossier['palette']);
	$sheet->setCellValue('M'.$row, $dossier['occ_article_palette']);
	$sheet->setCellValue('N'.$row, date('d-m-Y', strtotime($dossier['date_facture'])));
	$sheet->setCellValue('O'.$row, $article);
	$sheet->setCellValue('P'.$row, $dossier['ean_detail']);
	$sheet->setCellValue('Q'.$row, $dossier['serials']);
	$sheet->setCellValue('R'.$row, $dossier['descr']);
	$sheet->setCellValue('S'.$row, $dossier['fournisseur']);
	$sheet->setCellValue('T'.$row, $dossier['qte_cde']);
	$sheet->setCellValue('U'.$row, $dossier['tarif']);
	$sheet->setCellValue('V'.$row, $dossier['qte_litige']);
	$sheet->setCellValue('W'.$row, arrayToString($arReclam,$dossier['id_reclamation']));
	$sheet->setCellValue('X'.$row, $dossier['inv_article']);
	$sheet->setCellValue('Y'.$row, $dossier['inv_qte']);
	$sheet->setCellValue('Z'.$row, $dossier['inversion']);
	$sheet->setCellValue('AA'.$row, $dossier['inv_tarif']);
	$sheet->setCellValue('AB'.$row, $valoLig);
	$sheet->setCellValue('AC'.$row, arrayToString($arTransport, $dossier['id_transp']));
	$sheet->setCellValue('AD'.$row, arrayToString($arAffrete,$dossier['id_affrete']));
	$sheet->setCellValue('AE'.$row, arrayToString($arTransit, $dossier['id_transit']));
	$sheet->setCellValue('AF'.$row, arrayToString($arEquipe, $dossier['id_prepa']));
	$sheet->setCellValue('AG'.$row, arrayToString($arEquipe, $dossier['id_ctrl']));
	$sheet->setCellValue('AH'.$row, arrayToString($arEquipe, $dossier['id_chg']));
	$sheet->setCellValue('AI'.$row, $mtTransp);
	$sheet->setCellValue('AJ'.$row, $mtAssur);
	$sheet->setCellValue('AK'.$row, $mtFourn);
	$sheet->setCellValue('AL'.$row, $mtMag);
	$sheet->setCellValue('AM'.$row, $dossier['fac_mag']);
	$sheet->setCellValue('AN'.$row, arrayToString($arTypo, $dossier['id_typo']));
	$sheet->setCellValue('AO'.$row, arrayToString($arImputation,$dossier['id_imputation']));
	$sheet->setCellValue('AP'.$row, arrayToString($arAnalyse, $dossier['id_analyse']));
	$sheet->setCellValue('AQ'.$row, arrayToString($arConclusion, $dossier['id_conclusion']));

	$dossierW=$dossier['id_main'];
	$row++;
}




 // dimensionnement des colnes
$cols=['A','B','C','D','E','F','G', 'H', 'I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC', 'AD','AE','AF','AG', 'AH', 'AI','AJ', 'AK', 'AL','AM', 'AN', 'AO', 'AP'];
for ($i=0; $i < sizeof($cols) ; $i++)
{
	$sheet->getColumnDimension($cols[$i])->setAutoSize(true);
}
$sheet->setTitle('litiges');




// pour lancer le téléchargement sur le poste client
$writer = new Xlsx($spreadsheet);
$writer->save('export-litiges.xlsx');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="export-litiges.xlsx"');
$writer->save("php://output");
exit;

