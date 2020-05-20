<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}



//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------


require_once  '../../vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf();




$mpdf->Output('html.pdf',\Mpdf\Output\Destination::DOWNLOAD);





