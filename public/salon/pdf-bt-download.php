<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
  header('Location:'. ROOT_PATH.'/index.php');
  exit();
}
//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";



require_once '../../vendor/autoload.php';



//------------------------------------------------------
//			FONCTION
//------------------------------------------------------



function createZip($zip,$dir){
  if (is_dir($dir)){

    if ($dh = opendir($dir)){
       while (($file = readdir($dh)) !== false){

         // If file
         if (is_file($dir.$file)) {
            if($file != '' && $file != '.' && $file != '..'){

               $zip->addFile($dir.$file);
            }
         }else{
            // If directory
            if(is_dir($dir.$file) ){

              if($file != '' && $file != '.' && $file != '..'){

                // Add empty directory
                $zip->addEmptyDir($dir.$file);

                $folder = $dir.$file.'/';

                // Read data of the folder
                createZip($zip,$folder);
              }
            }

         }

       }
       closedir($dh);
     }
  }
}

// foreach ($listFournisseur as $key => $fournisseurs) {
// 	ob_start();
// 	include('badge-fournisseur.php');
// 	$html=ob_get_contents();
// 	ob_end_clean();
// 	$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);

// 	$mpdf->WriteHTML($html);
// 	// $pdfContent = $mpdf->Output();
// 	$mpdf->Output('D:\\www\_intranet\\_btlecest\\public\\salon\\pdf-fournisseur\\'.$fournisseurs['fournisseur'].'.pdf', 'F');
// }

$zip = new ZipArchive();
$filename = "./pdf-intern/badgesInterne.zip";


if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
	exit("cannot open <$filename>\n");
}

$dir = 'pdf-intern/';

  // Create zip
createZip($zip,$dir);

$zip->close();


 if (file_exists($filename)) {
     header('Content-Type: application/zip');
     header('Content-Disposition: attachment; filename="'.basename($filename).'"');
     header('Content-Length: ' . filesize($filename));

     flush();
     readfile($filename);
     // delete file
     unlink($filename);
 }


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