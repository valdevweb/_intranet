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

//------------------------------------------------------
// 			info
// 			voir si pb à partir de la 90eme colonne => non acceptée


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
$today=date("d-m-Y");
$echeance=new DateTime;
$echeance->modify('+ 30 days');
$echeance=$echeance->format('d-m-Y');
$mtBlanc=0;
$mtBrun=0;
$mtGris=0;
$mtFac=0;
$mtDeee=0;
$mtSacem=0;
$mtAvoir=0;
$mtAvoirDeee=0;
$mtAvoirSacem=0;





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
$numPalette=implode(', ',$numPalette);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'LIBELLE FACTURE');
$sheet->setCellValue('B1', 'Facturation palettes casse : '.$numPalette);
$sheet->setCellValue('A2', 'Date facture');
$sheet->setCellValue('B2', $today);
$sheet->setCellValue('A3', 'Date echeance');
$sheet->setCellValue('B3', $echeance);
$sheet->setCellValue('A4', 'Motif');
$sheet->setCellValue('B4', '?');
$sheet->setCellValue('A5', 'Dossiers');
$sheet->setCellValue('A6', 'Articles');
$sheet->setCellValue('A7', 'PU');
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

	$mtFac=$mtFac+($prixFac*$articles[$i]['uvc']);
	$mtDeee=$mtDeee+($articles[$i]['deee']*$articles[$i]['uvc']);
	$mtSacem=$mtSacem+($articles[$i]['sacem']*$articles[$i]['uvc']);

	$sheet->setCellValueByColumnAndRow($column, 5, $articles[$i]['dossier']);
	$sheet->setCellValueByColumnAndRow($column, 6, $articles[$i]['article']);
	$sheet->setCellValueByColumnAndRow($column, 7, str_replace('.',',',$prixFac));
	$sheet->setCellValueByColumnAndRow($column, 8, "QTE");
        // qte produit de produit pour le mag
	$sheet->setCellValueByColumnAndRow($column, 9, $articles[$i]['uvc']);
    // qte totale (sera tj égale à la qte pour le mag puisque un seul mag)
	$sheet->setCellValueByColumnAndRow($column, 10, $articles[$i]['uvc']);
    // montant total
	$sheet->setCellValueByColumnAndRow($column, 11, str_replace('.',',',$articles[$i]['uvc']* $prixFac));

    //  on avance d'un colonne mais on reste sur le même article
	$column=$column+1;

	// même produit => montant de sa deee avec code article de la deee
	$sheet->setCellValueByColumnAndRow($column, 5, $articles[$i]['dossier']);
	$sheet->setCellValueByColumnAndRow($column, 6, $codeDeee);
	$sheet->setCellValueByColumnAndRow($column, 7, str_replace('.',',',$articles[$i]['deee']));
	$sheet->setCellValueByColumnAndRow($column, 8, "QTE");
	$sheet->setCellValueByColumnAndRow($column, 9, $articles[$i]['uvc']);
	$sheet->setCellValueByColumnAndRow($column, 10, $articles[$i]['uvc']);
	$sheet->setCellValueByColumnAndRow($column, 11,str_replace('.',',',$articles[$i]['uvc']*$articles[$i]['deee']) );

	$column=$column+1;
	$sheet->setCellValueByColumnAndRow($column, 5, $articles[$i]['dossier']);
	$sheet->setCellValueByColumnAndRow($column, 6, $codeSacem);
	$sheet->setCellValueByColumnAndRow($column, 7, str_replace('.',',',$articles[$i]['sacem']));
	$sheet->setCellValueByColumnAndRow($column, 8, "QTE");
	$sheet->setCellValueByColumnAndRow($column, 9, $articles[$i]['uvc']);
	$sheet->setCellValueByColumnAndRow($column, 10, $articles[$i]['uvc']);
	$sheet->setCellValueByColumnAndRow($column, 11, str_replace('.',',',$articles[$i]['uvc']*$articles[$i]['sacem']));


	$i++;
}
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
$writer->setDelimiter(';');
$writer->setEnclosure('');
$writer->setLineEnding("\r\n");
$writer->setSheetIndex(0);
// $writer->save("facture_casse.csv");
$uploadDir= '..\..\..\upload\casse\\';
$writer->save($uploadDir.'\FACTCASSE '.date('dmy').'.csv');


// $listGt=['blanc', 'brun', 'gris'];
// $listComptes=['70717221', '70717223', '70717229'];


	if(!empty($articles))
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'LIBELLE FACTURE');
		$sheet->setCellValue('B1', 'Avoir palettes casse '.$numPalette);
		$sheet->setCellValue('A2', 'Date facture');
		$sheet->setCellValue('B2', $today);
		$sheet->setCellValue('A3', 'Date echeance');
		$sheet->setCellValue('B3', $echeance);
		$sheet->setCellValue('A4', 'Motif');
		$sheet->setCellValue('B4', '?');
		$sheet->setCellValue('A5', 'Dossiers');
		$sheet->setCellValue('A6', 'Articles');
		$sheet->setCellValue('A7', 'PU');
		$sheet->setCellValue('A8', 'MAGASIN');
		$sheet->setCellValue('A9', $articles[0]['btlec']);
		$sheet->setCellValue('A10', "Total Qte");
		$sheet->setCellValue('A11', 'Total MT');
// pour chauqe article, on a 3 colonne donc :
		$nbResult=3*(count($articles))+1;
		$i=0;
		for ($column = 2; $column <= $nbResult; $column++) {
    // on facture le pfnp - deee - sacem
			$prixFac=round(($articles[$i]['pfnp'] -$articles[$i]['deee']- $articles[$i]['sacem'])/2,2);
			$mtAvoir=$mtAvoir+($prixFac*$articles[$i]['uvc']);
			if($articles[$i]['gt']==1 || $articles[$i]['gt']==2){
				$mtBlanc=$mtBlanc + $prixFac;
			}elseif($articles[$i]['gt']==3 || $articles[$i]['gt']==4 || $articles[$i]['gt']==5){
				$mtBrun=$mtBrun+$prixFac;

			}
			elseif($articles[$i]['gt']==6 || $articles[$i]['gt']==7 || $articles[$i]['gt']==8 || $articles[$i]['gt']==9 || $articles[$i]['gt']==10){
				$mtGris=$mtGris + $prixFac;
			}


			if($articles[$i]['deee']>0){
				$deee=round(($articles[$i]['deee'] /2),2);
			}
			else{
				// pas de division par 0
				$deee=$articles[$i]['deee'];
			}
			if($articles[$i]['sacem'] >0){
				$sacem=round(($articles[$i]['sacem'] /2),2);
			}
			else{
				$sacem=$articles[$i]['sacem'];
			}
			$mtAvoirDeee=$mtAvoirDeee+($deee*$articles[$i]['uvc']);
			$mtAvoirSacem=$mtAvoirSacem+($sacem*$articles[$i]['uvc']);

			$sheet->setCellValueByColumnAndRow($column, 5, $articles[$i]['dossier']);
			$sheet->setCellValueByColumnAndRow($column, 6, $articles[$i]['article']);
			$sheet->setCellValueByColumnAndRow($column, 7, str_replace('.',',',$prixFac));
			$sheet->setCellValueByColumnAndRow($column, 8, "QTE");
        // qte produit de produit pour le mag
			$sheet->setCellValueByColumnAndRow($column, 9, '-'.$articles[$i]['uvc']);
    // qte totale (sera tj égale à la qte pour le mag puisque un seul mag)
			$sheet->setCellValueByColumnAndRow($column, 10, '-'.$articles[$i]['uvc']);
    // montant total
			$sheet->setCellValueByColumnAndRow($column, 11, str_replace('.',',',(-$articles[$i]['uvc']* $prixFac)));
    //  on avance d'un colonne mais on reste sur le même article
			$column=$column+1;

	// même produit => montant de sa deee avec code article de la deee
			$sheet->setCellValueByColumnAndRow($column, 5, $articles[$i]['dossier']);
			$sheet->setCellValueByColumnAndRow($column, 6, $codeDeee);
			$sheet->setCellValueByColumnAndRow($column, 7, str_replace('.',',',$articles[$i]['deee']));
			$sheet->setCellValueByColumnAndRow($column, 8, "QTE");
			$sheet->setCellValueByColumnAndRow($column, 9, '-'.$articles[$i]['uvc']);
			$sheet->setCellValueByColumnAndRow($column, 10, '-'.$articles[$i]['uvc']);
			$sheet->setCellValueByColumnAndRow($column, 11, str_replace('.',',',(-$articles[$i]['uvc']*$articles[$i]['deee'])));

			$column=$column+1;
			$sheet->setCellValueByColumnAndRow($column, 5, $articles[$i]['dossier']);
			$sheet->setCellValueByColumnAndRow($column, 6, $codeSacem);
			$sheet->setCellValueByColumnAndRow($column, 5, str_replace('.',',',$articles[$i]['sacem']));
			$sheet->setCellValueByColumnAndRow($column, 8, "QTE");
			$sheet->setCellValueByColumnAndRow($column, 9, '-'.$articles[$i]['uvc']);
			$sheet->setCellValueByColumnAndRow($column, 10, '-'.$articles[$i]['uvc']);
			$sheet->setCellValueByColumnAndRow($column, 11, str_replace('.',',',-($articles[$i]['uvc']*$articles[$i]['sacem'])));

			$i++;
		}


		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
		$writer->setDelimiter(';');
		$writer->setEnclosure('');
		$writer->setLineEnding("\r\n");
		$writer->setSheetIndex(0);
// $writer->save("facture_casse.csv");
		$uploadDir= '..\..\..\upload\casse\\';
		$writer->save($uploadDir.'\AVCASSE '.date('dmy').'.csv');

	}
$mtAvectaxes=$mtFac+$mtDeee+$mtSacem;
$mtAvoirAvecTaxes=$mtAvoir+$mtAvoirDeee+$mtAvoirSacem;



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
			if(file_exists($uploadDir.'\FACTCASSE '.date('dmy').'.csv')){
				echo '<a href="'.UPLOAD_DIR.'\casse\FACTCASSE '.date('dmy').'.csv" onclick="confirmFac('.$idExp.')"><i class="fas fa-download pr-3" ></i>facture casse - expe '.$idExp.'</a>, montant de la facture : '.$mtAvectaxes.'&euro; ('. $mtFac.'&euro; + '.$mtDeee.'&euro; DEEE + '.+$mtSacem.'&euro; SACEM)<br>';
			}
				if(file_exists($uploadDir.'\AVCASSE '.date('dmy').'.csv')){
					echo '<a href="'.UPLOAD_DIR.'\casse\AVCASSE '.date('dmy').'.csv"><i class="fas fa-download pr-3"></i>avoir casse - expe '.$idExp.'</a>, montant de l\'avoir : '.$mtAvoirAvecTaxes.'&euro; ('. $mtAvoir.'&euro; + '.$mtAvoirDeee.'&euro; DEEE + '.+$mtAvoirSacem.'&euro; SACEM)<br>';
					echo 'Montant avoir séparé blanc : '.$mtBlanc.'&euro; <br>';
					echo 'Montant avoir séparé brun : '.$mtBrun.'&euro; <br>';
					echo 'Montant avoir séparé gris : '.$mtGris.'&euro; <br>';
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