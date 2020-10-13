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


function getParticipant($pdoBt)
{
	$req=$pdoBt->prepare("SELECT mag, salon_2019.galec, sca3.btlec, centrale, nom, prenom, fonction, DATE_FORMAT(date_saisie,'%d-%m-%Y') as datesaisie, mardi, mercredi,repas_mardi, repas_mercredi FROM salon_2019
		LEFT JOIN sca3 ON salon_2019.galec=sca3.galec
		LEFT JOIN salon_fonction ON salon_2019.id_fonction=salon_fonction.id
		WHERE salon_2019.galec !='' ORDER BY sca3.mag");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$participantList=getParticipant($pdoBt);
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
$sheet->setCellValue('A1', 'Déno');
$sheet->setCellValue('B1', 'BTLec');
$sheet->setCellValue('C1', 'Galec');
$sheet->setCellValue('D1', 'Centrale');
$sheet->setCellValue('E1', 'Nom');
$sheet->setCellValue('F1', 'Prénom');
$sheet->setCellValue('G1', 'Fonction');
$sheet->setCellValue('H1', 'Mardi');
$sheet->setCellValue('I1', 'Repas mardi');
$sheet->setCellValue('J1', 'Mercredi');
$sheet->setCellValue('K1', 'Repas mercredi');
$row=2;
$nbMardi=0;
$nbRepasMardi=0;
$nbMercr=0;
$nbRepasMercr=0;


foreach ($participantList as $part)
{
	$sheet->setCellValue('A'.$row, $part['mag']);
	$sheet->setCellValue('B'.$row, $part['btlec']);
	$sheet->setCellValue('C'.$row, $part['galec']);
	$sheet->setCellValue('D'.$row, $part['centrale']);
	$sheet->setCellValue('E'.$row, $part['nom']);
	$sheet->setCellValue('F'.$row, $part['prenom']);
	$sheet->setCellValue('G'.$row, $part['fonction']);
	$sheet->setCellValue('H'.$row, $part['mardi']);
	$sheet->setCellValue('I'.$row, $part['repas_mardi']);
	$sheet->setCellValue('J'.$row, $part['mercredi']);
	$sheet->setCellValue('K'.$row, $part['repas_mercredi']);
	$nbMardi=$nbMardi+$part['mardi'];
	$nbRepasMardi=$nbRepasMardi+$part['repas_mardi'];
	$nbMercr=$nbMercr+$part['mercredi'];
	$nbRepasMercr=$nbRepasMercr+$part['repas_mercredi'];
	$row++;
}
	$sheet->setCellValue('H'.$row, $nbMardi);
	$sheet->setCellValue('I'.$row, $nbRepasMardi);
	$sheet->setCellValue('J'.$row, $nbMercr);
	$sheet->setCellValue('K'.$row, $nbRepasMercr);
$cols=['A','B','C','D','E','F','G', 'H', 'I', 'J', 'K'];
for ($i=0; $i < sizeof($cols) ; $i++)
{
	$sheet->getColumnDimension($cols[$i])->setAutoSize(true);
}
$sheet->setTitle('salon2019');

// pour lancer le téléchargement sur le poste client
$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="participants salon 2019.xlsx"');
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