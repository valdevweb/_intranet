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
// $codeDeee="170277";
// $codeSacem="170279";
// $codeMeuble="171498";
$today=date("d-m-Y");
$echeance=new DateTime;
$echeance->modify('+ 30 days');
$echeance=$echeance->format('d-m-Y');
$mtBlanc=0;
$mtBrun=0;
$mtGris=0;
$mtFac=0;
$mtAvoir=0;






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
$maxcol=98;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'LIBELLE FACTURE');
$sheet->setCellValue('B1', 'Facturation palettes casse : '.$numPalette);
$sheet->setCellValue('A2', 'Date facture');
$sheet->setCellValue('B2', $today);
$sheet->setCellValue('A3', 'Date echeance');
$sheet->setCellValue('B3', $echeance);
$sheet->setCellValue('A4', 'Motif');
$sheet->setCellValue('B4', '001');
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

$i=0;
for ($column = 2; $column <= count($articles)+1; $column++) {
	// on facture le pfnp - deee - sacem
	$prixFac=$articles[$i]['pfnp'];

	$mtFac=$mtFac+($prixFac*$articles[$i]['uvc']);

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
	// col montant  deee ou ecomeuble

	$i++;
}
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
$writer->setDelimiter(';');
$writer->setEnclosure('');
$writer->setLineEnding("\r\n");
$writer->setSheetIndex(0);
// $writer->save("facture_casse.csv");
$uploadDir= '..\..\..\upload\casse\\';
$writer->save($uploadDir.'\FACTCASSE_'.date('dmy').'.csv');


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
	$sheet->setCellValue('B4', '001');
	$sheet->setCellValue('A5', 'Dossiers');
	$sheet->setCellValue('A6', 'Articles');
	$sheet->setCellValue('A7', 'PU');
	$sheet->setCellValue('A8', 'MAGASIN');
	$sheet->setCellValue('A9', $articles[0]['btlec']);
	$sheet->setCellValue('A10', "Total Qte");
	$sheet->setCellValue('A11', 'Total MT');
// pour chauqe article, on a 3 colonne donc :
	$i=0;
	for ($column = 2; $column <= count($articles)+1; $column++) {
	    // on facture le pfnp - deee - sacem
    // on ne s'occupe pas du total de la taxe eco meuble puisqu'on affiche pas le détail des montants des taxes
    // la taxe est bien prise en compte puisque elle est dans le champ deee
		$prixFac=round(($articles[$i]['pfnp'])/2,2);
		$mtAvoir=$mtAvoir+($prixFac*$articles[$i]['uvc']);
		if($articles[$i]['gt']==1 || $articles[$i]['gt']==2){
			$mtBlanc=$mtBlanc + ($prixFac*$articles[$i]['uvc']);
		}elseif($articles[$i]['gt']==3 || $articles[$i]['gt']==4 || $articles[$i]['gt']==5){
			$mtBrun=$mtBrun+($prixFac*$articles[$i]['uvc']);

		}
		elseif($articles[$i]['gt']==6 || $articles[$i]['gt']==7 || $articles[$i]['gt']==8 || $articles[$i]['gt']==9 || $articles[$i]['gt']==10){
			$mtGris=$mtGris + ($prixFac*$articles[$i]['uvc']);
		}

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
		$i++;
	}


	$writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
	$writer->setDelimiter(';');
	$writer->setEnclosure('');
	$writer->setLineEnding("\r\n");
	$writer->setSheetIndex(0);
// $writer->save("facture_casse.csv");
	$uploadDir= '..\..\..\upload\casse\\';
	$writer->save($uploadDir.'\AVCASSE_'.date('dmy').'.csv');

}

if(isset($mtFac) && isset($mtBlanc) && isset($mtGris) && isset($mtBrun) && $mtFac!=0){
	$req=$pdoCasse->prepare("UPDATE exps SET mt_fac= :mt_fac, mt_blanc= :mt_blanc, mt_gris= :mt_gris, mt_brun= :mt_brun, date_fac= :date_fac WHERE id= :id");
	$req->execute([
		':mt_fac' =>$mtFac,
		':mt_blanc'	=>$mtBlanc,
		':mt_gris'=>$mtGris,
		':mt_brun'=>$mtBrun,
		':date_fac'=>date('Y-m-d H:i:s'),
		':id'	=>$_GET['id']
	]);
	$maj=$req->rowCount();
	if($maj!=1){
		$errors[]="impossible de mettre à jour la base de donnée";
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

	<div id="result"></div>


	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<div class="pricing-table">
				<header class="pricing-header-plus">
				<!-- 			<span data-tooltip class="top" tabindex="2" title="Plus Basic Information.">
					<i class="fas fa-info-circle"></i></span> -->
					<!-- <a href="#"><p>Plus</p></a> -->
					<div class="price-box">
						<p class="title"><?=number_format((float)$mtFac,2,'.',' ')?><span class="">&euro;</span></p>
						<h5>Facture</h5>
					</div>
				</header>
				<div class="pricing-content-plus">
					<ul>
						<li class="bullet-item">
							<i class="fa fa-plus"></i>Hors taxe :
							<div class="pricingh-basic float-right pr-5 "><?=number_format((float)$mtFac,2,'.', ' ')?>&euro;</div>
						</li>

					</ul>
					<?php if(file_exists($uploadDir.'\FACTCASSE_'.date('dmy').'.csv')): ?>
						<a href="<?=UPLOAD_DIR.'\casse\FACTCASSE_'.date('dmy')?>.csv" onclick="confirmFac('.$idExp.')" class="flash"><button type="button" class="button btn-plus">Télécharger</button></a>

					<?php endif ?>
				</div>
			</div>
		</div>
		<div class="col-1"></div>
		<div class="col">
			<div class="pricing-table">
				<header class="pricing-header-premium">
					<div class="price-box">
						<p class="title"><?=number_format((float)$mtAvoir,2,'.',' ')?>&euro;</p>
						<h5>Avoir</h5>
					</div>
				</header>
				<div class="pricing-content-premium">
					<ul>
						<li class="bullet-item">
							<i class="fa fa-plus"></i>Blanc :
							<div class="float-right pr-5 pricingh-plus"><?=number_format((float)$mtBlanc,2,'.',' ')?>&euro;</div>
						</li>
						<li class="bullet-item">
							<i class="fa fa-plus"></i>Brun :

							<div class="float-right pr-5  pricingh-plus"><?=number_format((float)$mtBrun,2,'.',' ')?>&euro;</div>
						</li>
						<li class="bullet-item">
							<i class="fa fa-plus"></i>Gris :
							<div class="float-right pr-5  pricingh-plus"><?=number_format((float)$mtGris,2,'.',' ')?>&euro;</div>
						</li>

					</ul>
					<?php if(file_exists($uploadDir.'\AVCASSE_'.date('dmy').'.csv')): ?>
						<a href="<?=UPLOAD_DIR.'\casse\AVCASSE_'.date('dmy')?>.csv" class="flash"><button type="button" class="button btn-premium">Télécharger</button></a>

					<?php endif ?>
				</div>
			</div>
		</div>
		<div class="col-lg-1"></div>

	</div>
	<!-- </section> -->

	<div class="row mt-5">
		<div class="col-lg-1"></div>
		<div class="col text-center">
			<a href="casse-clos.php?id=<?=$_GET['id']?>">Clôturer l'expédition</a>
		</div>
		<div class="col-lg-1"></div>
	</div>



	<div class="row py-5"></div>




	<!-- ./container -->
</div>


<?php
require '../view/_footer-bt.php';
?>