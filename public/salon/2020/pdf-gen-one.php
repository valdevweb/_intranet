<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
require '../../config/db-connect.php';

//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";



require_once '../../vendor/autoload.php';
require_once '../../Class/mag/MagHelpers.php';

//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// $descr="saisie déclaration mag hors qlik" ;
// $page=basename(__file__);
// $action="";
// // addRecord($pdoStat,$page,$action, $descr,$code=null,$detail=null)
// addRecord($pdoStat,$page,$action, $descr, 208);



//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

function getThisInvitation($pdoBt)
{
	$req=$pdoBt->prepare("SELECT * FROM salon_2020 LEFT JOIN qrcode ON salon_2020.id=qrcode.id LEFT JOIN salon_fonction ON id_fonction= salon_fonction.id WHERE salon_2020.id= :id");
	$req->execute(array(
		':id'	=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}
$invit=getThisInvitation($pdoBt);




ob_start();
// include('pdf-invit2020.php');
include('badge-single.php');
$html=ob_get_contents();
ob_end_clean();
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);

// $mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$pdfContent = $mpdf->Output();

//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

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