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
function search($pdoCasse)
{

	if($_GET['statut']==''){
		$etat=' etat IS NOT NULL ';
	}
	elseif($_GET['statut']==0){
		$etat=' etat=0 ';
	}
	elseif($_GET['statut']==1){
		$etat=' etat =1 ';

	}

	$req=$pdoCasse->prepare("SELECT DATE_FORMAT(date_casse,'%d-%m-%y') as dateCasse, nb_colis,
		categories.categorie,
		article, dossier, gt, designation, pcb, uvc, valo, pu, fournisseur, mt_decote,etat, detruit,cmt,DATE_FORMAT(date_clos, '%d-%m-%y') as dateClos,
		origines.origine,
		type_casse.type,
		palettes.palette, palettes.contremarque
		FROM casses
		LEFT JOIN categories ON id_categorie=categories.id
		LEFT JOIN origines ON id_origine=origines.id
		LEFT JOIN type_casse ON id_type =type_casse.id
		LEFT JOIN palettes ON id_palette=palettes.id
		WHERE concat(article,casses.id) LIKE :search AND date_casse BETWEEN :date_start AND :date_end AND $etat");
	$req->execute(array(
		':search' =>'%'.$_GET['search_strg'] .'%',
		':date_start'		=>$_GET['date_start'],
		':date_end'			=>$_GET['date_end']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
 // return $req->errorInfo();
}
if(isset($_GET)){
	$listCasse=search($pdoCasse);


}

//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Date casse');
$sheet->setCellValue('B1', 'Article');
$sheet->setCellValue('C1', 'Dossier');
$sheet->setCellValue('D1', 'Désignation');
$sheet->setCellValue('E1', 'GT');
$sheet->setCellValue('F1', 'Catégorie');
$sheet->setCellValue('G1', 'PU');
$sheet->setCellValue('H1', 'PCB');
$sheet->setCellValue('I1', 'Nb colis');
$sheet->setCellValue('J1', 'UVC');
$sheet->setCellValue('K1', 'Valo');
$sheet->setCellValue('L1', 'Decote');
$sheet->setCellValue('M1', 'Détruit');
$sheet->setCellValue('N1', 'Commentaire');
$sheet->setCellValue('O1', 'Origine');
$sheet->setCellValue('P1', 'Type');
$sheet->setCellValue('Q1', 'Palette');
$sheet->setCellValue('R1', 'Palette contremarque');
$sheet->setCellValue('S1', 'Date clôture');

$styleArray = [
	'font' => [
		'bold' => true,
		'color'=>['rgb'=>'0075bc']
	],
	'borders' => [
		'top' => [
			'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
		],
	],
];

$row=2;
$spreadsheet->getActiveSheet()->getStyle('A1:S1')->applyFromArray($styleArray);
foreach ($listCasse as $key => $casse)
{
	if($casse['detruit']==1)
	{
		$destroy='oui';
	}
	else
	{
		$destroy='non';
	}
	$sheet->setCellValue('A'.$row, $casse['dateCasse']);
	$sheet->setCellValue('B'.$row, $casse['article']);
	$sheet->setCellValue('C'.$row, $casse['dossier']);
	$sheet->setCellValue('D'.$row, $casse['designation']);
	$sheet->setCellValue('E'.$row, $casse['gt']);
	$sheet->setCellValue('F'.$row, $casse['categorie']);
	$sheet->setCellValue('G'.$row, $casse['pu']);
	$sheet->setCellValue('H'.$row, $casse['pcb']);
	$sheet->setCellValue('I'.$row, $casse['nb_colis']);
	$sheet->setCellValue('J'.$row, $casse['uvc']);
	$sheet->setCellValue('K'.$row, $casse['valo']);
	$sheet->setCellValue('L'.$row, $casse['mt_decote']);
	$sheet->setCellValue('M'.$row, $destroy);
	$sheet->setCellValue('N'.$row, $casse['cmt']);
	$sheet->setCellValue('O'.$row, $casse['origine']);
	$sheet->setCellValue('P'.$row, $casse['type']);
	$sheet->setCellValue('Q'.$row, $casse['palette']);
	$sheet->setCellValue('R'.$row, $casse['contremarque']);
	$sheet->setCellValue('S'.$row, $casse['dateClos']);

	$row++;
}




 // dimensionnement des colnes
$cols=['A','B','C','D','E','F','G', 'H', 'I','J','K','L','M','N','O','P','Q','R','S'];
for ($i=0; $i < sizeof($cols) ; $i++)
{
	$sheet->getColumnDimension($cols[$i])->setAutoSize(true);
}
$sheet->setTitle('casses');




// pour lancer le téléchargement sur le poste client
$writer = new Xlsx($spreadsheet);
$writer->save('export-casse.xlsx');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="export-casse.xlsx"');
$writer->save("php://output");
exit;





//------------------------------------------------------
//			VIEW
//------------------------------------------------------
// include('../view/_head-bt.php');
// include('../view/_navbar.php');
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