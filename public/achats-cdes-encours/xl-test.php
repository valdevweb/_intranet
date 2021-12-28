<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}

$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";


require '../../Class/Db.php';
require '../../Class/Helpers.php';

require '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');

$templateTrp = 'test.xlsx';
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($templateTrp);

$sheet = $spreadsheet->getActiveSheet();
$row=2;

$sheet->setCellValue('A1',  "protégée");
$sheet->setCellValue('C1',  "non protégée");

$val="123456";
if(preg_match("/^[0-9]+$/",$val)){
	echo "numerique";
}else{
	echo "non numerique";

}


$filename="protection.xlsx";


// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
// header('Content-Disposition: attachment;filename="'.$filename.'"');
// header('Cache-Control: max-age=0');

// $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
// $writer->save('php://output');
// exit();






//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div id="container" class="container">
	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue">Main title</h1>
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














