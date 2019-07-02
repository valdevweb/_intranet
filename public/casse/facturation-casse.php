<?php
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


//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
require_once '../../vendor/autoload.php';
require('casse-getters.fn.php');
require ('../../Class/Helpers.php');




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
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];
$idExp=$_GET['id'];
$codeDeee="170277";
$codeSacem="170279";
//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
//----------------------------------------------
//  		excel
//----------------------------------------------
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$articles=getExpPaletteCasse($pdoCasse,$idExp);
$numPalette=getPaletteList($pdoCasse,$idExp);
	echo "<pre>";
	print_r($numPalette);
	echo '</pre>';
	$numPalette=implode(', ',$numPalette);
	echo "<pre>";
	print_r($numPalette);
	echo '</pre>';
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'LIBELLE FACTURE');
$sheet->setCellValue('B1', 'Facturation palettes casse : '.$numPalette);
$sheet->setCellValue('A2', 'CPT VENTES');
$sheet->setCellValue('B2', '70715100');
$sheet->setCellValue('A3', 'ARTICLES');
$sheet->setCellValue('A4', 'DESIGNATIONS');
$sheet->setCellValue('A5', 'PU');
$sheet->setCellValue('A6', 'TVA');
$sheet->setCellValue('A7', 'COTISATION');
$sheet->setCellValue('A8', 'MAGASIN');
$sheet->setCellValue('A9', $articles[0]['btlec']);
$sheet->setCellValue('A10', "Total Qte");
$sheet->setCellValue('A11', 'Total MT');
	// echo "<pre>";
	// print_r($articles);
	// echo '</pre>';
// pour chauqe article, on a 3 colonne donc :
$nbResult=3*(count($articles))+1;

$i=0;
for ($column = 2; $column <= $nbResult; $column++) {
	// on facture le pfnp - deee - sacem
	$prixFac=$articles[$i]['pfnp'] -$articles[$i]['deee']- $articles[$i]['sacem'];
	$sheet->setCellValueByColumnAndRow($column, 3, $articles[$i]['article']);
	$sheet->setCellValueByColumnAndRow($column, 4, $articles[$i]['designation']);
	$sheet->setCellValueByColumnAndRow($column, 5, $prixFac);
	$sheet->setCellValueByColumnAndRow($column, 6, 6);
	$sheet->setCellValueByColumnAndRow($column, 7, 9);
	$sheet->setCellValueByColumnAndRow($column, 8, "QTE");
        // qte produit de produit pour le mag
	$sheet->setCellValueByColumnAndRow($column, 9, $articles[$i]['uvc']);
    // qte totale (qd plusieur mag = somme de toutes les qte)
	$sheet->setCellValueByColumnAndRow($column, 10, $articles[$i]['uvc']);
    // montant total
	$sheet->setCellValueByColumnAndRow($column, 11, $articles[$i]['uvc']* $prixFac);
    //  on avance d'un colonne mais on reste sur le même article
	$column=$column+1;
	$sheet->setCellValueByColumnAndRow($column, 3, $codeDeee);
	$sheet->setCellValueByColumnAndRow($column, 4, 'D3E');
	$sheet->setCellValueByColumnAndRow($column, 5, $articles[$i]['deee']);
	$sheet->setCellValueByColumnAndRow($column, 6, 6);
	$sheet->setCellValueByColumnAndRow($column, 7, 9);
	$sheet->setCellValueByColumnAndRow($column, 8, "QTE");
	$sheet->setCellValueByColumnAndRow($column, 9, $articles[$i]['uvc']);
	$sheet->setCellValueByColumnAndRow($column, 10, $articles[$i]['uvc']);
	$sheet->setCellValueByColumnAndRow($column, 11, $articles[$i]['uvc']*$articles[$i]['deee'] );

	$column=$column+1;
	$sheet->setCellValueByColumnAndRow($column, 3, $codeSacem);
	$sheet->setCellValueByColumnAndRow($column, 4, 'SACEM');
	$sheet->setCellValueByColumnAndRow($column, 5, $articles[$i]['sacem']);
	$sheet->setCellValueByColumnAndRow($column, 6, 6);
	$sheet->setCellValueByColumnAndRow($column, 7, 9);
	$sheet->setCellValueByColumnAndRow($column, 8, "QTE");
	$sheet->setCellValueByColumnAndRow($column, 9, $articles[$i]['uvc']);
	$sheet->setCellValueByColumnAndRow($column, 10, $articles[$i]['uvc']);
	$sheet->setCellValueByColumnAndRow($column, 11, $articles[$i]['uvc']*$articles[$i]['sacem'] );


	$i++;
}
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
$writer->setDelimiter(';');
$writer->setEnclosure('');
$writer->setLineEnding("\r\n");
$writer->setSheetIndex(0);
// $writer->save("facture_casse.csv");
$uploadDir= '..\..\..\upload\casse\\';
$writer->save($uploadDir.'\facture_casse - expe '.$idExp.'.csv');


$listGt=['blanc', 'brun', 'gris'];
$listComptes=['70717221', '70717223', '70717229'];



for ($g=0; $g < count($listGt); $g++) {
	$articles=getArticleByGt($pdoCasse, $idExp, $listGt[$g]);
	if(!empty($articles))
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'LIBELLE FACTURE');
		$sheet->setCellValue('B1', 'Avoir palettes casse '.$numPalette.' - GT '.$listGt[$g]);
		$sheet->setCellValue('A2', 'CPT VENTES');
		$sheet->setCellValue('B2', $listComptes[$g]);
		$sheet->setCellValue('A3', 'ARTICLES');
		$sheet->setCellValue('A4', 'DESIGNATIONS');
		$sheet->setCellValue('A5', 'PU');
		$sheet->setCellValue('A6', 'TVA');
		$sheet->setCellValue('A7', 'COTISATION');
		$sheet->setCellValue('A8', 'MAGASIN');
// code du mag
		$sheet->setCellValue('A9', $articles[0]['btlec']);
		$sheet->setCellValue('A10', "Total Qte");
		$sheet->setCellValue('A11', 'Total MT');
// pour chauqe article, on a 3 colonne donc :
		$nbResult=3*(count($articles))+1;

		$i=0;
		for ($column = 2; $column <= $nbResult; $column++) {
    // on facture le pfnp - deee - sacem
			$prixFac=-round(($articles[$i]['pfnp'] -$articles[$i]['deee']- $articles[$i]['sacem'])/2,2);
			if($articles[$i]['deee']>0){
				$deee=-round(($articles[$i]['deee'] /2),2);
			}
			else{
				$deee=-$articles[$i]['deee'];
			}
			if($articles[$i]['sacem'] >0){
				$sacem=-round(($articles[$i]['sacem'] /2),2);
			}
			else{
				$sacem=-$articles[$i]['sacem'];
			}


			$sheet->setCellValueByColumnAndRow($column, 3, $articles[$i]['article']);
			$sheet->setCellValueByColumnAndRow($column, 4, $articles[$i]['designation']);
			$sheet->setCellValueByColumnAndRow($column, 5, $prixFac);
			$sheet->setCellValueByColumnAndRow($column, 6, 6);
			$sheet->setCellValueByColumnAndRow($column, 7, 9);
			$sheet->setCellValueByColumnAndRow($column, 8, "QTE");
        // qte produit de produit pour le mag
			$sheet->setCellValueByColumnAndRow($column, 9, $articles[$i]['uvc']);
    // qte totale (qd plusieur mag = somme de toutes les qte)
			$sheet->setCellValueByColumnAndRow($column, 10, $articles[$i]['uvc']);
    // montant total
			$sheet->setCellValueByColumnAndRow($column, 11, $articles[$i]['uvc']* $prixFac);
    //  on avance d'un colonne mais on reste sur le même article
			$column=$column+1;
			$sheet->setCellValueByColumnAndRow($column, 3, $codeDeee);
			$sheet->setCellValueByColumnAndRow($column, 4, 'D3E');
			$sheet->setCellValueByColumnAndRow($column, 5, $deee);
			$sheet->setCellValueByColumnAndRow($column, 6, 6);
			$sheet->setCellValueByColumnAndRow($column, 7, 9);
			$sheet->setCellValueByColumnAndRow($column, 8, "QTE");
			$sheet->setCellValueByColumnAndRow($column, 9, $articles[$i]['uvc']);
			$sheet->setCellValueByColumnAndRow($column, 10, $articles[$i]['uvc']);
			$sheet->setCellValueByColumnAndRow($column, 11, $articles[$i]['uvc']*$deee );

			$column=$column+1;
			$sheet->setCellValueByColumnAndRow($column, 3, $codeSacem);
			$sheet->setCellValueByColumnAndRow($column, 4, 'SACEM');
			$sheet->setCellValueByColumnAndRow($column, 5, $sacem);
			$sheet->setCellValueByColumnAndRow($column, 6, 6);
			$sheet->setCellValueByColumnAndRow($column, 7, 9);
			$sheet->setCellValueByColumnAndRow($column, 8, "QTE");
			$sheet->setCellValueByColumnAndRow($column, 9, $articles[$i]['uvc']);
			$sheet->setCellValueByColumnAndRow($column, 10, $articles[$i]['uvc']);
			$sheet->setCellValueByColumnAndRow($column, 11, $articles[$i]['uvc']*$sacem );


			$i++;
		}


		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
		$writer->setDelimiter(';');
		$writer->setEnclosure('');
		$writer->setLineEnding("\r\n");
		$writer->setSheetIndex(0);
// $writer->save("facture_casse.csv");
		$uploadDir= '..\..\..\upload\casse\\';
		$writer->save($uploadDir.'\avoir casse - '.$listGt[$g].' - expe '.$idExp.'.csv');

	}
}



//------------------------------------------------------
//			VIEW
//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');

?>

<div class="container">
	<?= Helpers::returnBtn('bt-casse-dashboard.php'); ?>

	<h1 class="text-main-blue pb-5 ">Facturation de l'expédition <?=$idExp?></h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>
	<div class="row ">
		<div class="col">
			<p>Télécharger les factures : </p>
		</div>
	</div>
	<div class="row pb-5">
		<div class="col">
			<?php
			if(file_exists($uploadDir.'\facture_casse - expe '.$idExp.'.csv')){
				echo '<a href="'.UPLOAD_DIR.'\casse\facture_casse - expe '.$idExp.'.csv" onclick="confirmFac('.$idExp.')"><i class="fas fa-download pr-3" ></i>facture casse - expe '.$idExp.'</a><br>';
			}
			for ($g=0; $g < count($listGt); $g++) {
				if(file_exists($uploadDir.'\avoir casse - '.$listGt[$g].' - expe '.$idExp.'.csv')){
					echo '<a href="'.UPLOAD_DIR.'\casse\avoir casse - '.$listGt[$g].' - expe '.$idExp.'.csv"><i class="fas fa-download pr-3"></i>avoir casse - '.$listGt[$g].' - expe '.$idExp.'</a><br>';
				}
			}


			?>
		</div>
	</div>
	<div id="result"></div>

	<div class="row py-5"></div>




	<!-- ./container -->
</div>
<script type="text/javascript">

    function confirmFac(idExp) {
        if (confirm('Ajouter la date du jour en date de facturation et clôturer le dossier ?')) {

            $.ajax({
                url: "casse-clos.php",
                type: "POST",
                data: {id : idExp},

                success: function(html) {
                    $('#result').append(html);
                }
            });

        }
    }

</script>

<?php
require '../view/_footer-bt.php';
?>