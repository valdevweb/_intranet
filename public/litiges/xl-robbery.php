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
		SELECT dossiers.id as id_main, dossiers.dossier, date_crea, DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea, user_crea, dossiers.galec, etat_dossier, vingtquatre,
		deno, magasin.centrales.centrale, magasin.mag.id as btlec,
		transporteur, affrete, transit,
		etat,
		mt_transp, mt_assur, mt_fourn, mt_mag, fac_mag,
		details.palette, details.facture, DATE_FORMAT(details.date_facture, '%d-%m-%Y') as datefacture, details.article, details.ean, details.dossier_gessica, details.descr, details.qte_cde, details.tarif, details.fournisseur, qte_litige,
		reclamation, id_reclamation, valo, imputation, typo, analyse, conclusion, puv, pul


		FROM dossiers
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN magasin.mag ON dossiers.galec=magasin.mag.galec
		LEFT JOIN magasin.centrales ON magasin.mag.centrale=magasin.centrales.id_ctbt
		LEFT JOIN transporteur ON id_transp=transporteur.id
		LEFT JOIN affrete ON id_affrete=affrete.id
		LEFT JOIN transit ON id_transit=transit.id
		LEFT JOIN details ON dossiers.id=details.id_dossier
		LEFT JOIN reclamation ON id_reclamation=reclamation.id
		LEFT JOIN imputation ON id_imputation=imputation.id
		LEFT JOIN typo ON id_typo=typo.id
		LEFT JOIN analyse ON id_analyse=analyse.id
		LEFT JOIN conclusion ON id_conclusion=conclusion.id
		WHERE id_robbery= :id_robbery
		ORDER BY dossiers.dossier DESC");
	$req->execute([
		':id_robbery'	=>$_GET['id']
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
	// return $req->errorInfo();
}

$dossiers=getAllDossier($pdoLitige);

function getPaletteCde($pdoLitige,$id_dossier)
{
	$req=$pdoLitige->prepare("SELECT sum(tarif) as tarif_palette_cde, sum(qte_cde) as qte_art_cde FROM details WHERE id_dossier= :id_dossier");
	$req->execute(array(
		':id_dossier'	=>$id_dossier
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

function getPaletteRecu($pdoLitige, $id_dossier)
{
	$req=$pdoLitige->prepare("SELECT sum(tarif) as tarif_palette_recu, sum(qte_cde) as qte_art_recu FROM palette_inv WHERE id_dossier= :id_dossier");
	$req->execute(array(
		':id_dossier'	=>$id_dossier
	));
	return $req->fetch(PDO::FETCH_ASSOC);

}


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
$sheet->setCellValue('S1', 'PUV');
$sheet->setCellValue('T1', 'PUL');
$sheet->setCellValue('U1', 'Valo');
$sheet->setCellValue('V1', 'Transporteur');
$sheet->setCellValue('W1', 'Affreteur');
$sheet->setCellValue('X1', 'Transit');
$sheet->setCellValue('YD1', 'Réglement Transporteur');
$sheet->setCellValue('Z1', 'Réglement assurance');
$sheet->setCellValue('AA1', 'Réglement fournisseur');
$sheet->setCellValue('AB1', 'Réglement magasin');
$sheet->setCellValue('AC1', 'Facture magasin');
$sheet->setCellValue('AD1', 'Typologie');
$sheet->setCellValue('AE1', 'Imputation');
$sheet->setCellValue('AF1', 'Analyse');
$sheet->setCellValue('AG1', 'Réponse');

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

$spreadsheet->getActiveSheet()->getStyle('A1:AG1')->applyFromArray($styleArrayDossier);

$dossierInversionPalette='';
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
	// calcul valo par article
	// cas général
	// $valoLig=($dossier['tarif']/$dossier['qte_cde'])*$dossier['qte_litige'];
	// si excédent => id_reclamation= 6, 9 (palette) moins
	// si inversion =>id_reclamation =5
	// => ($dossier['tarif']/$dossier['qte_cde'])*$dossier['qte_litige'])-($dossier['inv_qte']*$dossier['inv_tarif'])
	//inversion de palette = 7
	if($dossier['id_reclamation']==6)
	{
		$valoLig=-($dossier['tarif']/$dossier['qte_cde'])*$dossier['qte_litige'];

	}
	elseif($dossier['id_reclamation']==5)
	{
		$valoLig=(($dossier['tarif']/$dossier['qte_cde'])*$dossier['qte_litige'])-($dossier['inv_qte']*$dossier['inv_tarif']);
	}


	else
	{
		$valoLig=($dossier['tarif']/$dossier['qte_cde'])*$dossier['qte_litige'];
	}




		$sheet->setCellValue('A'.$row, $dossier['dossier']);
		$sheet->setCellValue('B'.$row, $dossier['datecrea']);
		$sheet->setCellValue('C'.$row, $dossier['deno']);
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
		$sheet->setCellValue('S'.$row, $dossier['puv']);
		$sheet->setCellValue('T'.$row, $dossier['pul']);
		$sheet->setCellValue('U'.$row, $valoLig);
		$sheet->setCellValue('V'.$row, $dossier['transporteur']);
		$sheet->setCellValue('W'.$row, $dossier['affrete']);
		$sheet->setCellValue('X'.$row, $dossier['transit']);
		$sheet->setCellValue('Y'.$row, $dossier['mt_transp']);
		$sheet->setCellValue('Z'.$row, $dossier['mt_assur']);
		$sheet->setCellValue('AA'.$row, $dossier['mt_fourn']);
		$sheet->setCellValue('AB'.$row, $dossier['mt_mag']);
		$sheet->setCellValue('AC'.$row, $dossier['fac_mag']);
		$sheet->setCellValue('AD'.$row, $dossier['typo']);
		$sheet->setCellValue('AE'.$row, $dossier['imputation']);
		$sheet->setCellValue('AF'.$row, $dossier['analyse']);
		$sheet->setCellValue('AG'.$row, $dossier['conclusion']);

		$row++;




	}
 // dimensionnement des colnes
	$cols=['A','B','C','D','E','F','G', 'H', 'I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC', 'AD','AE','AF','AG'];
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