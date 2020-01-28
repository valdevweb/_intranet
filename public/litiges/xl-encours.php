<?php

 // require('../../config/pdo_connect.php');
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}

require_once '../../vendor/autoload.php';

//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";
//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
function getAllDossier($pdoLitige)
{
	$req=$pdoLitige->prepare("
		SELECT dossiers.id as id_main, dossiers.dossier,date_crea,DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea,user_crea,dossiers.galec,etat_dossier, mag, centrale, sca3.btlec,vingtquatre, etat, transporteur, affrete, transit, CONCAT(prepa.nom,' ', prepa.prenom) as fullprepa, CONCAT(ctrl.nom,' ',ctrl.prenom) as fullctrl,CONCAT(chg.nom,' ',chg.prenom) as fullchg, mt_transp, mt_assur, mt_fourn, mt_mag, fac_mag,
		details.palette, details.facture, DATE_FORMAT(details.date_facture, '%d-%m-%Y') as datefacture ,details.article, details.ean, details.dossier_gessica, details.descr, details.qte_cde, details.tarif, details.fournisseur, qte_litige, inv_palette, inv_article,inv_descr, inv_tarif, inv_fournisseur,inv_qte, reclamation, id_reclamation, valo,inversion,imputation, typo,analyse, conclusion, valo_line,
		qte_litige, inv_article,inv_descr, inv_tarif, inv_fournisseur,inv_qte, reclamation, id_reclamation, valo,inversion,imputation, typo,analyse, conclusion

		FROM dossiers
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN btlec.sca3 ON dossiers.galec=btlec.sca3.galec
		LEFT JOIN transporteur ON id_transp=transporteur.id
		LEFT JOIN affrete ON id_affrete=affrete.id
		LEFT JOIN transit ON id_transit=transit.id
		LEFT JOIN equipe as prepa ON id_prepa=prepa.id
		LEFT JOIN equipe as ctrl ON id_ctrl=ctrl.id
		LEFT JOIN equipe as chg ON id_chg=chg.id
		LEFT JOIN equipe as ctrl_stock ON id_ctrl_stock=ctrl_stock.id
		LEFT JOIN details ON dossiers.id=details.id_dossier
		LEFT JOIN reclamation ON id_reclamation=reclamation.id
		LEFT JOIN imputation ON id_imputation=imputation.id
		LEFT JOIN typo ON id_typo=typo.id
		LEFT JOIN analyse ON id_analyse=analyse.id
		LEFT JOIN conclusion ON id_conclusion=conclusion.id
		ORDER BY dossiers.dossier DESC");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
	// return $req->errorInfo();
}

$dossiers=getAllDossier($pdoLitige);

// function getPaletteCde($pdoLitige,$id_dossier)
// {
// 	$req=$pdoLitige->prepare("SELECT sum(tarif) as tarif_palette_cde, sum(qte_cde) as qte_art_cde FROM details WHERE id_dossier= :id_dossier");
// 	$req->execute(array(
// 		':id_dossier'	=>$id_dossier
// 	));
// 	return $req->fetch(PDO::FETCH_ASSOC);
// }

function getPaletteRecu($pdoLitige, $id_dossier)
{
	$req=$pdoLitige->prepare("SELECT dossiers.id as id_main, dossiers.dossier,DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea, mag, sca3.btlec, dossiers.galec,centrale, etat, vingtquatre,etat_dossier, palette_inv.palette as palette,DATE_FORMAT(details.date_facture, '%d-%m-%Y') as datefacture, palette_inv.article, palette_inv.ean,palette_inv.descr, palette_inv.qte_cde, palette_inv.tarif as valo_line,palette_inv.fournisseur, palette_inv.cnuf,
CONCAT(prepa.nom,' ', prepa.prenom) as fullprepa, CONCAT(ctrl.nom,' ',ctrl.prenom) as fullctrl,CONCAT(chg.nom,' ',chg.prenom) as fullchg,
 mt_transp, mt_assur, mt_fourn, mt_mag, fac_mag, imputation, typo,analyse, conclusion, transporteur, transit, affrete

	 FROM palette_inv
		LEFT JOIN dossiers ON palette_inv.id_dossier=dossiers.id
		LEFT JOIN btlec.sca3 ON dossiers.galec=btlec.sca3.galec
		LEFT JOIN transporteur ON id_transp=transporteur.id
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN affrete ON id_affrete=affrete.id
		LEFT JOIN transit ON id_transit=transit.id
		LEFT JOIN equipe as prepa ON id_prepa=prepa.id
		LEFT JOIN equipe as ctrl ON id_ctrl=ctrl.id
		LEFT JOIN equipe as chg ON id_chg=chg.id
		LEFT JOIN equipe as ctrl_stock ON id_ctrl_stock=ctrl_stock.id
		LEFT JOIN details ON dossiers.id=details.id_dossier
		LEFT JOIN reclamation ON id_reclamation=reclamation.id
		LEFT JOIN imputation ON id_imputation=imputation.id
		LEFT JOIN typo ON id_typo=typo.id
		LEFT JOIN analyse ON id_analyse=analyse.id
		LEFT JOIN conclusion ON id_conclusion=conclusion.id
		WHERE palette_inv.id_dossier= :id_dossier GROUP BY palette_inv.id");
	$req->execute(array(
		':id_dossier'	=>$id_dossier
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);

}


//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

$listDossierInvPalette=[];

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
$sheet->setCellValue('H1', '24/48h');
$sheet->setCellValue('I1', 'Soldé');
$sheet->setCellValue('J1', 'palette');
$sheet->setCellValue('K1', 'date facture');
$sheet->setCellValue('L1', 'article');
$sheet->setCellValue('M1', 'ean');
$sheet->setCellValue('N1', 'Désignation');
$sheet->setCellValue('O1', 'Fournisseur');
$sheet->setCellValue('P1', 'Quantité commandée');
$sheet->setCellValue('Q1', 'Tarif');
$sheet->setCellValue('R1', 'Quantité litige');
$sheet->setCellValue('S1', 'Réclamation');
$sheet->setCellValue('T1', 'Article reçu');
$sheet->setCellValue('U1', 'Quantité reçue');
$sheet->setCellValue('V1', 'EAN / palette reçu');
$sheet->setCellValue('W1', 'Tarif article reçu');
$sheet->setCellValue('X1', 'Valo');
$sheet->setCellValue('Y1', 'Transporteur');
$sheet->setCellValue('Z1', 'Affreteur');
$sheet->setCellValue('AA1', 'Transit');
$sheet->setCellValue('AB1', 'Preparateur');
$sheet->setCellValue('AC1', 'Contrôleur');
$sheet->setCellValue('AD1', 'Chargeur');
$sheet->setCellValue('AE1', 'Réglement Transporteur');
$sheet->setCellValue('AF1', 'Réglement assurance');
$sheet->setCellValue('AG1', 'Réglement fournisseur');
$sheet->setCellValue('AH1', 'Réglement magasin');
$sheet->setCellValue('AI1', 'Facture magasin');
$sheet->setCellValue('AJ1', 'Typologie');
$sheet->setCellValue('AK1', 'Imputation');
$sheet->setCellValue('AL1', 'Analyse');
$sheet->setCellValue('AM1', 'Réponse');

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

$spreadsheet->getActiveSheet()->getStyle('A1:AL1')->applyFromArray($styleArray);

$spreadsheet->getActiveSheet()->getStyle('A1:I1')->applyFromArray($styleArrayDossier);
$spreadsheet->getActiveSheet()->getStyle('J1:W1')->applyFromArray($styleArrayDetail);
$spreadsheet->getActiveSheet()->getStyle('X1:Z1')->applyFromArray($styleArrayAutre);
$spreadsheet->getActiveSheet()->getStyle('AA1:AC1')->applyFromArray($styleArrayEquipe);
$spreadsheet->getActiveSheet()->getStyle('AD1:AI1')->applyFromArray($styleArrayMontant);
$spreadsheet->getActiveSheet()->getStyle('AJ1:AM1')->applyFromArray($styleArrayAnalyse);


$row=2;
foreach ($dossiers as $key => $dossier){

	if($dossier['vingtquatre']==1){
		$vingtquatre='oui';
	}else{
		$vingtquatre='non';
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
	}

	else{
		$valoLig= $dossier['valo_line'];

	}

	$solde=($dossier['etat_dossier']==0)?'non':'oui';
	$sheet->setCellValue('A'.$row, $dossier['dossier']);
	$sheet->setCellValue('B'.$row, $dossier['datecrea']);
	$sheet->setCellValue('C'.$row, $dossier['mag']);
	$sheet->setCellValue('D'.$row, $dossier['btlec']);
	$sheet->setCellValue('E'.$row, $dossier['galec']);
	$sheet->setCellValue('F'.$row, $dossier['centrale']);
	$sheet->setCellValue('G'.$row, $dossier['etat']);
	$sheet->setCellValue('H'.$row, $vingtquatre);
	$sheet->setCellValue('I'.$row, $solde);
	$sheet->setCellValue('J'.$row, $dossier['palette']);
	$sheet->setCellValue('K'.$row, $dossier['datefacture']);
	$sheet->setCellValue('L'.$row, $dossier['article']);
	$sheet->setCellValue('M'.$row, $dossier['ean']);
	$sheet->setCellValue('N'.$row, $dossier['descr']);
	$sheet->setCellValue('O'.$row, $dossier['fournisseur']);
	$sheet->setCellValue('P'.$row, $dossier['qte_cde']);
	$sheet->setCellValue('Q'.$row, $dossier['tarif']);
	$sheet->setCellValue('R'.$row, $dossier['qte_litige']);
	$sheet->setCellValue('S'.$row, $dossier['reclamation']);
	$sheet->setCellValue('T'.$row, $dossier['inv_article']);
	$sheet->setCellValue('U'.$row, $dossier['inv_qte']);
	$sheet->setCellValue('V'.$row, $dossier['inversion']);
	$sheet->setCellValue('W'.$row, $dossier['inv_tarif']);
	$sheet->setCellValue('X'.$row, $valoLig);
	$sheet->setCellValue('Y'.$row, $dossier['transporteur']);
	$sheet->setCellValue('Z'.$row, $dossier['affrete']);
	$sheet->setCellValue('AA'.$row, $dossier['transit']);
	$sheet->setCellValue('AB'.$row, $dossier['fullprepa']);
	$sheet->setCellValue('AC'.$row, $dossier['fullctrl']);
	$sheet->setCellValue('AD'.$row, $dossier['fullchg']);
	$sheet->setCellValue('AE'.$row, $dossier['mt_transp']);
	$sheet->setCellValue('AF'.$row, $dossier['mt_assur']);
	$sheet->setCellValue('AG'.$row, $dossier['mt_fourn']);
	$sheet->setCellValue('AH'.$row, $dossier['mt_mag']);
	$sheet->setCellValue('AI'.$row, $dossier['fac_mag']);
	$sheet->setCellValue('AJ'.$row, $dossier['typo']);
	$sheet->setCellValue('AK'.$row, $dossier['imputation']);
	$sheet->setCellValue('AL'.$row, $dossier['analyse']);
	$sheet->setCellValue('AM'.$row, $dossier['conclusion']);

	$row++;
}


if(!empty($listDossierInvPalette)){
	for ($i=0; $i <count($listDossierInvPalette) ; $i++) {
		$dossiers=getPaletteRecu($pdoLitige, $listDossierInvPalette[$i]);


		foreach ($dossiers as $key => $dossier){

			if($dossier['vingtquatre']==1){
				$vingtquatre='oui';
			}else{
				$vingtquatre='non';
			}
			$vide="";
			$solde=($dossier['etat_dossier']==0)?'non':'oui';
			$sheet->setCellValue('A'.$row, $dossier['dossier']);
			$sheet->setCellValue('B'.$row, $dossier['datecrea']);
			$sheet->setCellValue('C'.$row, $dossier['mag']);
			$sheet->setCellValue('D'.$row, $dossier['btlec']);
			$sheet->setCellValue('E'.$row, $dossier['galec']);
			$sheet->setCellValue('F'.$row, $dossier['centrale']);
			$sheet->setCellValue('G'.$row, $dossier['etat']);
			$sheet->setCellValue('H'.$row, $vingtquatre);
			$sheet->setCellValue('I'.$row, $solde);
			$sheet->setCellValue('J'.$row, $dossier['palette']);
			$sheet->setCellValue('K'.$row, $dossier['datefacture']);
			$sheet->setCellValue('L'.$row, $dossier['article']);
			$sheet->setCellValue('M'.$row, $dossier['ean']);
			$sheet->setCellValue('N'.$row, $dossier['descr']);
			$sheet->setCellValue('O'.$row, $dossier['fournisseur']);
			$sheet->setCellValue('P'.$row, $dossier['qte_cde']);
			$sheet->setCellValue('Q'.$row, $dossier['valo_line']);
			$sheet->setCellValue('R'.$row, $dossier['qte_cde']);
			$sheet->setCellValue('S'.$row, "Inversion palette");
			$sheet->setCellValue('T'.$row, $vide);
			$sheet->setCellValue('U'.$row, $vide);
			$sheet->setCellValue('V'.$row, $vide);
			$sheet->setCellValue('W'.$row, $vide);
			$sheet->setCellValue('X'.$row, -$dossier['valo_line']);
			$sheet->setCellValue('Y'.$row, $dossier['transporteur']);
			$sheet->setCellValue('Z'.$row, $dossier['affrete']);
			$sheet->setCellValue('AA'.$row, $dossier['transit']);
			$sheet->setCellValue('AB'.$row, $dossier['fullprepa']);
			$sheet->setCellValue('AC'.$row, $dossier['fullctrl']);
			$sheet->setCellValue('AD'.$row, $dossier['fullchg']);
			$sheet->setCellValue('AE'.$row, $dossier['mt_transp']);
			$sheet->setCellValue('AF'.$row, $dossier['mt_assur']);
			$sheet->setCellValue('AG'.$row, $dossier['mt_fourn']);
			$sheet->setCellValue('AH'.$row, $dossier['mt_mag']);
			$sheet->setCellValue('AI'.$row, $dossier['fac_mag']);
			$sheet->setCellValue('AJ'.$row, $dossier['typo']);
			$sheet->setCellValue('AK'.$row, $dossier['imputation']);
			$sheet->setCellValue('AL'.$row, $dossier['analyse']);
			$sheet->setCellValue('AM'.$row, $dossier['conclusion']);

			$row++;

		}

	}
}

 // dimensionnement des colnes
$cols=['A','B','C','D','E','F','G', 'H', 'I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC', 'AD','AE','AF','AG', 'AH', 'AI','AJ', 'AK', 'AL','AM'];
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





//------------------------------------------------------
//			VIEW
//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>
<!--********************************
DEBUT CONTENU CONTAINER
*********************************-->
<div class="container">
	<h1 class="text-main-blue py-5 ">Main title</h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>


	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>