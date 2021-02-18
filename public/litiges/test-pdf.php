<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}
require '../../config/db-connect.php';

require_once '../../vendor/autoload.php';



//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";



$errors=[];
$success=[];


		//----------------------------------------------
		//  		PDF
		//----------------------------------------------

		// récupération du contenu html du pdf
		ob_start();
		include('pdf-fiche-suivi.php');
		$html=ob_get_contents();
		ob_end_clean();
		$path='http://172.30.92.53/'.VERSION.'upload/litiges/'.$html;

		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML($path);
		// $pdfContent = $mpdf->Output('', 'S');
		$pdfContent = $mpdf->Output();

		// --------------------------------------
		// destinataires

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
	<div class="row">
		<div class="col-lg-1 col-xxl-2"></div>

		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>

</div>







<?php

require '../view/_footer-bt.php';

?>