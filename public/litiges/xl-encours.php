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
		SELECT dossiers.id as id_main,dossiers.dossier,date_crea,DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea,user_crea,dossiers.galec,etat_dossier, mag, centrale, sca3.btlec,vingtquatre, etat, transporteur, affrete, transit, CONCAT(prepa.nom,' ', prepa.prenom) as fullprepa, CONCAT(ctrl.nom,' ',ctrl.prenom) as fullctrl,CONCAT(chg.nom,' ',chg.prenom) as fullchg, mt_transp, mt_assur, mt_fourn, mt_mag, fac_mag, palette, facture, DATE_FORMAT(date_facture, '%d-%m-%Y') as datefacture ,article, ean, dossier_gessica, descr, qte_cde, tarif, fournisseur, qte_litige, inv_article,inv_descr, inv_tarif, inv_fournisseur,inv_qte, reclamation, valo,inversion,imputation, typo,analyse, conclusion
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
}

$dossiers=getAllDossier($pdoLitige);
//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

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
$sheet->setCellValue('I1', 'palette');
$sheet->setCellValue('J1', 'date facture');
$sheet->setCellValue('K1', 'article');
$sheet->setCellValue('L1', 'ean');
$sheet->setCellValue('M1', 'Désignation');
$sheet->setCellValue('N1', 'Fournisseur');
$sheet->setCellValue('O1', 'Quantité commandée');
$sheet->setCellValue('P1', 'Tarif');
$sheet->setCellValue('Q1', 'Quantité litige');
$sheet->setCellValue('R1', 'Réclamation');
$sheet->setCellValue('S1', 'Article reçu');
$sheet->setCellValue('T1', 'Quantité reçue');
$sheet->setCellValue('U1', 'EAN article reçu');
$sheet->setCellValue('V1', 'Tarif article reçu');
$sheet->setCellValue('W1', 'Transporteur');
$sheet->setCellValue('X1', 'Affreteur');
$sheet->setCellValue('Y1', 'Transit');
$sheet->setCellValue('Z1', 'Preparateur');
$sheet->setCellValue('AA1', 'Contrôleur');
$sheet->setCellValue('AB1', 'Chargeur');
$sheet->setCellValue('AC1', 'Réglement Transporteur');
$sheet->setCellValue('AD1', 'Réglement assurance');
$sheet->setCellValue('AE1', 'Réglement fournisseur');
$sheet->setCellValue('AF1', 'Réglement magasin');
$sheet->setCellValue('AG1', 'Facture magasin');
$sheet->setCellValue('AH1', 'Valo totale');
$sheet->setCellValue('AI1', 'Typologie');
$sheet->setCellValue('AJ1', 'Imputation');
$sheet->setCellValue('AK1', 'Analyse');
$sheet->setCellValue('AL1', 'Réponse');

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

$spreadsheet->getActiveSheet()->getStyle('A1:H1')->applyFromArray($styleArrayDossier);
$spreadsheet->getActiveSheet()->getStyle('I1:V1')->applyFromArray($styleArrayDetail);
$spreadsheet->getActiveSheet()->getStyle('W1:Y1')->applyFromArray($styleArrayAutre);
$spreadsheet->getActiveSheet()->getStyle('Z1:AB1')->applyFromArray($styleArrayEquipe);
$spreadsheet->getActiveSheet()->getStyle('AC1:AH1')->applyFromArray($styleArrayMontant);
$spreadsheet->getActiveSheet()->getStyle('AJ1:AL1')->applyFromArray($styleArrayAnalyse);


$row=2;
foreach ($dossiers as $key => $dossier)
{
	if($dossier['vingtquatre']==1)
	{
		$vingtquatre='oui';
	}
	else
	{
		$vingtquatre='non';
	}
	$sheet->setCellValue('A'.$row, $dossier['dossier']);
	$sheet->setCellValue('B'.$row, $dossier['datecrea']);
	$sheet->setCellValue('C'.$row, $dossier['mag']);
	$sheet->setCellValue('D'.$row, $dossier['btlec']);
	$sheet->setCellValue('E'.$row, $dossier['galec']);
	$sheet->setCellValue('F'.$row, $dossier['centrale']);
	$sheet->setCellValue('G'.$row, $dossier['etat']);
	$sheet->setCellValue('H'.$row, $vingtquatre);
	$sheet->setCellValue('I'.$row, $dossier['palette']);
	$sheet->setCellValue('J'.$row, $dossier['datefacture']);
	$sheet->setCellValue('K'.$row, $dossier['article']);
	$sheet->setCellValue('L'.$row, $dossier['ean']);
	$sheet->setCellValue('M'.$row, $dossier['descr']);
	$sheet->setCellValue('N'.$row, $dossier['fournisseur']);
	$sheet->setCellValue('O'.$row, $dossier['qte_cde']);
	$sheet->setCellValue('P'.$row, $dossier['tarif']);
	$sheet->setCellValue('Q'.$row, $dossier['qte_litige']);
	$sheet->setCellValue('R'.$row, $dossier['reclamation']);
	$sheet->setCellValue('S'.$row, $dossier['inv_article']);
	$sheet->setCellValue('T'.$row, $dossier['inv_qte']);
	$sheet->setCellValue('U'.$row, $dossier['inversion']);
	$sheet->setCellValue('V'.$row, $dossier['inv_tarif']);
	$sheet->setCellValue('W'.$row, $dossier['transporteur']);
	$sheet->setCellValue('X'.$row, $dossier['affrete']);
	$sheet->setCellValue('Y'.$row, $dossier['transit']);
	$sheet->setCellValue('Z'.$row, $dossier['fullprepa']);
	$sheet->setCellValue('AA'.$row, $dossier['fullctrl']);
	$sheet->setCellValue('AB'.$row, $dossier['fullchg']);
	$sheet->setCellValue('AC'.$row, $dossier['mt_transp']);
	$sheet->setCellValue('AD'.$row, $dossier['mt_assur']);
	$sheet->setCellValue('AE'.$row, $dossier['mt_fourn']);
	$sheet->setCellValue('AF'.$row, $dossier['mt_mag']);
	$sheet->setCellValue('AG'.$row, $dossier['fac_mag']);
	$sheet->setCellValue('AH'.$row, $dossier['valo']);
	$sheet->setCellValue('AI'.$row, $dossier['typo']);
	$sheet->setCellValue('AJ'.$row, $dossier['imputation']);
	$sheet->setCellValue('AK'.$row, $dossier['analyse']);
	$sheet->setCellValue('AL'.$row, $dossier['conclusion']);

	$row++;
 // dimensionnement des colnes
	$cols=['A','B','C','D','E','F','G', 'H', 'I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC', 'AD','AE','AF','AG', 'AH', 'AI','AJ', 'AK'];
	for ($i=0; $i < sizeof($cols) ; $i++)
	{
		$sheet->getColumnDimension($cols[$i])->setAutoSize(true);
	}
	$sheet->setTitle('litiges');

}


// pour lancer le téléchargement sur le poste client
$writer = new Xlsx($spreadsheet);
// $writer->save('export-litiges.xlsx');
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