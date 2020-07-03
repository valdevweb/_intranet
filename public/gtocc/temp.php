<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	echo "pas de variable session";

}
//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";

require_once '../../vendor/autoload.php';
require_once '../../Class/OccPaletteMgr.php';
require '../../Class/UserHelpers.php';
require '../../Class/OccHelpers.php';


$errors=[];
$success=[];


function getQuantiteActuelle($pdoBt,$article){
	$req=$pdoBt->prepare("SELECT * FROM occ_article_qlik WHERE article_qlik= :article_qlik");
	$req->execute([
		':article_qlik'	=>$article

	]);
	return $req->fetch(PDO::FETCH_ASSOC);

}

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;




			// envoi mail

$paletteMgr=new OccPaletteMgr($pdoBt);

$infoCde=$paletteMgr->getCdeByIdCde(12);
$infoMag=UserHelpers::getMagInfoByIdWebUser($pdoUser, $pdoMag, $infoCde[0]['id_web_user']);

$arrayListPalette=OccHelpers::arrayPalette($pdoBt);
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


$mpdf->Output($pdfName,\Mpdf\Output\Destination::INLINE);






//------------------------------------------------------
//			FONCTION
//------------------------------------------------------


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
