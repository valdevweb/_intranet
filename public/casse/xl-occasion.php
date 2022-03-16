<?php
require('../../config/autoload.php');
if (!isset($_SESSION['id'])) {
	header('Location:' . ROOT_PATH . '/index.php');
	exit();
}

$pageCss = explode(".php", basename(__file__));
$pageCss = $pageCss[0];
$cssFile = ROOT_PATH . "/public/css/" . $pageCss . ".css";


require '../../Class/Db.php';
require_once '../../vendor/autoload.php';
require('../../Class/casse/TrtDao.php');
require('../../Class/casse/ExpDao.php');
require('../../Class/CrudDao.php');


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$errors = [];
$success = [];
$db = new Db();
$pdoUser = $db->getPdo('web_users');
$pdoCasse = $db->getPdo('casse');

$trtDao = new TrtDao($pdoCasse);
$expDao = new ExpDao($pdoCasse);
$casseCrud = new CrudDao($pdoCasse);

$articles = $expDao->getExpPaletteCasse($_GET['id']);


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Article');
$sheet->setCellValue('B1', 'Désignation');
$sheet->setCellValue('C1', 'Quantité');
$sheet->setCellValue('D1', 'N° palette');
$sheet->setCellValue('E1', 'PAF unitaire moins 40');
$sheet->setCellValue('F1', 'Prix de vente conseillé occasion TTC');
$sheet->setCellValue('G1', 'Taux de marge magasin');
$sheet->setCellValue('H1', 'Article palette G6K');
$sheet->setCellValue('I1', 'Article BT');
$row = 2;

foreach ($articles as $key => $art) {
	$paf = 0;
	$sheet->setCellValue('A' . $row,  $art['ean']);
	$sheet->getStyle('A' . $row)
		->getNumberFormat()
		->setFormatCode(
			'0000000000000'
		);
	$sheet->setCellValue('B' . $row,  $art['designation']);
	$sheet->setCellValue('C' . $row,  $art['uvc']);
	if ($art['pfnp'] != null) {
		$paf = $art['pfnp'] / 2 + round($art['pfnp'] / 10);
	}
	$sheet->setCellValue('D' . $row,  $art['contremarque']);

	$sheet->setCellValue('E' . $row,  $paf);
	$sheet->setCellValue('F' . $row,  $art['ppi']);
	$sheet->setCellValue('I' . $row,  $art['article']);
	$row++;
}



$writer = new Xlsx($spreadsheet);
$xlFile = 'occasion/fichier-occasion-exp-'.$_GET['id'] . '.xlsx';
$xlPath = DIR_UPLOAD . $xlFile;
$writer->save($xlPath);

$casseCrud->update("exps", "id=" . $_GET['id'], ['file_occ' => $xlFile]);


if (VERSION == "_") {
	$dest = ['valerie.montusclat@btlecest.leclerc'];
	$cc = [];
} else {

	$dest = ['jonathan.domange@btlecest.leclerc'];
	$cc = ['valerie.montusclat@btlecest.leclerc', 'nathalie.pazik@btlecest.leclerc', 'christelle.trousset@btlecest.leclerc'];
}


$transport = (new Swift_SmtpTransport(SMTP_ADDRESS, 25));
$mailer = new Swift_Mailer($transport);

$htmlMail = file_get_contents('occasion-excel-casse.html');
$htmlMail = str_replace('{PROD}', $prod, $htmlMail);
$subject = 'Portail BTLec Est - fichier excel casse/occasion - expédition '.$_GET['id'];
$message = (new Swift_Message($subject))
	->setBody($htmlMail, 'text/html')
	->setFrom(EMAIL_NEPASREPONDRE)
	->setTo($dest)
	->setCc($cc)
	->attach(Swift_Attachment::fromPath($xlPath));
if (!$mailer->send($message, $failures)) {
	print_r($failures);
} else {
	$trtDao->insertTrtHisto($_GET['id'], $_GET['id_trt']);
	header('Location:casse-dashboard.php?#exp-' . $_GET['id']);
}





//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div id="container" class="container">
	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue">Génération du fichier excel</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>
</div>

<?php
require '../view/_footer-bt.php';
?>