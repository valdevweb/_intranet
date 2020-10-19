<?php

 // require('../../config/pdo_connect.php');
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}
//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";

require_once  '../../vendor/autoload.php';
require_once  '../../Class/LitigeDao.php';
require_once  '../../Class/MagHelpers.php';

//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

// function getMagLitiges($pdoLitige)
// {
// 	$req=$pdoLitige->prepare("SELECT dossier,DATE_FORMAT(date_crea,'%d-%m-%Y')as datecrea, typo, imputation, etat, tablegt.gt, valo, analyse, conclusion, mt_transp, mt_assur, mt_fourn, mt_mag, btlec.sca3.mag, btlec.sca3.btlec, btlec.sca3.centrale  FROM dossiers
// 		LEFT JOIN btlec.sca3 ON dossiers.galec=btlec.sca3.galec
// 		LEFT JOIN typo ON dossiers.id_typo=typo.id
// 		LEFT JOIN imputation ON dossiers.id_imputation=imputation.id
// 		LEFT JOIN gt as tablegt ON dossiers.id_gt=tablegt.id
// 		LEFT JOIN etat ON dossiers.id_etat=etat.id
// 		LEFT JOIN gt ON dossiers.id_gt=gt.id
// 		LEFT JOIN analyse ON dossiers.id_analyse=analyse.id
// 		LEFT JOIN conclusion ON dossiers.id_conclusion=conclusion.id



// 		WHERE dossiers.galec= :galec");
// 	$req->execute(array(
// 		':galec'	=>$_GET['galec']
// 	));
// 	return $req->fetchAll(PDO::FETCH_ASSOC);
// }

function getFinance($pdoQlik, $btlec, $year){
	$req=$pdoQlik->prepare("SELECT CA_Annuel FROM statsventesadh WHERE CodeBtlec= :btlec AND AnneeCA= :year");
	$req->execute(array(
		':btlec' =>$btlec,
		':year'	=>$year
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}



// $listLitige=getMagLitiges($pdoLitige);
// 	echo "<pre>";
// 	print_r($listLitige);
// 	echo '</pre>';
$litigeDao=new LitigeDao($pdoLitige);
$listLitige=$litigeDao->getLitigesByGalec($_GET['galec']);
	// echo "<pre>";
	// print_r($listLitigeM);
	// echo '</pre>';


$nbLitiges=count($listLitige);
$valoTotal=0;
foreach ($listLitige as $litige)
{
	$valoTotal=$valoTotal+$litige['valo'];
}
$valoTotal=number_format((float)$valoTotal,2,'.','');






$yearN=date('Y');
$yearNUn= date("Y",strtotime("-1 year"));
$yearNDeux= date("Y",strtotime("-2 year"));
$financeN=getFinance($pdoQlik,$listLitige[0]['btlec'],$yearN);
$financeNUn=getFinance($pdoQlik,$listLitige[0]['btlec'],$yearNUn);
$financeNDeux=getFinance($pdoQlik,$listLitige[0]['btlec'],$yearNDeux);




ob_start();
include('pdf/pdf-stat-litige-mag.php');
$html=ob_get_contents();
ob_end_clean();
$footer='<p class="footer">BTLEC EST - 2 rue des Moissons - Parc d\'activité Witry Caurel - 51420 Witry les Reims</p>';

$mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);
$mpdf->SetHTMLFooter($footer);
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