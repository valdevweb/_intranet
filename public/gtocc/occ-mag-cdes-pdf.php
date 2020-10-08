<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	echo "pas de variable session";

}


//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
require_once '../../vendor/autoload.php';
require_once '../../Class/OccPaletteMgr.php';
require '../../Class/UserHelpers.php';
require '../../Class/OccHelpers.php';

//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];



//------------------------------------------------------
//			FONCTION
//------------------------------------------------------


$infoMag=UserHelpers::getMagInfoByIdWebUser($pdoUser, $pdoMag, $_SESSION['id_web_user']);
$paletteMgr=new OccPaletteMgr($pdoOcc);

$infoCde=$paletteMgr->getCdeByIdCde($_GET['id']);
$arrayListPalette=OccHelpers::arrayPalette($pdoOcc);

	// génération du pdf
$mpdf = new \Mpdf\Mpdf();
ob_start();
include('pdf-cmd-mag.php');
$html=ob_get_contents();
ob_end_clean();


$mpdf->WriteHTML($html);
	// $mpdf->Output('test.pdf',\Mpdf\Output\Destination::DOWNLOAD);
$pdfName='BL - cde Leclerc Occasion n.'.$_GET['id'] .'.pdf';


$pdfContent = $mpdf->Output();


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

</div>

<?php
require '../view/_footer-bt.php';
?>


