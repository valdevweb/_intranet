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

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$articles=getExpPaletteCasse($pdoCasse,$idExp);
$numPalette=getPaletteList($pdoCasse,$idExp);
$numPalette=implode(', ',$numPalette);


//----------------------------------------------
//  		FACTURE
//----------------------------------------------
// 1- création du fichier
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
// 2-ajout entête
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


$column=2;
$i=0;
// si plus de 99 colonne, il faut faire un nouveau fichier facture
$maxcol=99;
$uploadDir= DIR_UPLOAD.'casse\\';
$nbFac=$nbAvoir=0;

for($i=0;$i<count($articles);$i++){
	if($column==$maxcol-1){
		// ajout dernière données sur cette feuille
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
		$sheet->setCellValueByColumnAndRow($column, 11, str_replace('.',',',$articles[$i]['uvc']* $prixFac));
		// mise au format csv
		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
		$writer->setDelimiter(';');
		$writer->setEnclosure('');
		$writer->setLineEnding("\r\n");
		$writer->setSheetIndex(0);
		$nbFac++;
		// sauvegarde
		$writer->save($uploadDir.'\FACTCASSE_'.date('dmy').$nbFac.'.csv');
		//réinitialisation colonne pour nouveau doc + nv doc + intitulé
		$column=2;
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
	}else{
		//ajout données
		$prixFac=$articles[$i]['pfnp'];
		$mtFac=$mtFac+($prixFac*$articles[$i]['uvc']);
		$sheet->setCellValueByColumnAndRow($column, 5, $articles[$i]['dossier']);
		$sheet->setCellValueByColumnAndRow($column, 6, $articles[$i]['article']);
		$sheet->setCellValueByColumnAndRow($column, 7, str_replace('.',',',$prixFac));
		$sheet->setCellValueByColumnAndRow($column, 8, "QTE");
		$sheet->setCellValueByColumnAndRow($column, 9, $articles[$i]['uvc']);
		$sheet->setCellValueByColumnAndRow($column, 10, $articles[$i]['uvc']);
		$sheet->setCellValueByColumnAndRow($column, 11, str_replace('.',',',$articles[$i]['uvc']* $prixFac));
		$column++;
	}

}
//  mise au format facture en cours
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
$writer->setDelimiter(';');
$writer->setEnclosure('');
$writer->setLineEnding("\r\n");
$writer->setSheetIndex(0);
// sauvegarde facture en cours
// pour éviter le 0 si une seule facture
$nbFac=($nbFac==0)?'':$nbFac+1;
$writer->save($uploadDir.'\FACTCASSE_'.date('dmy').$nbFac.'.csv');

//----------------------------------------------
//  		AVOIR
//----------------------------------------------
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

//reinitialise
$column=2;
$i=0;

for($i=0;$i<count($articles);$i++){
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


	if($column==$maxcol){
		$sheet->setCellValueByColumnAndRow($column, 5, $articles[$i]['dossier']);
		$sheet->setCellValueByColumnAndRow($column, 6, $articles[$i]['article']);
		$sheet->setCellValueByColumnAndRow($column, 7, str_replace('.',',',$prixFac));
		$sheet->setCellValueByColumnAndRow($column, 8, "QTE");
		$sheet->setCellValueByColumnAndRow($column, 9, '-'.$articles[$i]['uvc']);
		$sheet->setCellValueByColumnAndRow($column, 10, '-'.$articles[$i]['uvc']);
		$sheet->setCellValueByColumnAndRow($column, 11, str_replace('.',',',(-$articles[$i]['uvc']* $prixFac)));
		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
		$writer->setDelimiter(';');
		$writer->setEnclosure('');
		$writer->setLineEnding("\r\n");
		$writer->setSheetIndex(0);
		$nbAvoir++;
		$writer->save($uploadDir.'\AVCASSE_'.date('dmy').$nbAvoir.'.csv');
		$column=2;
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

	}else{
		$sheet->setCellValueByColumnAndRow($column, 5, $articles[$i]['dossier']);
		$sheet->setCellValueByColumnAndRow($column, 6, $articles[$i]['article']);
		$sheet->setCellValueByColumnAndRow($column, 7, str_replace('.',',',$prixFac));
		$sheet->setCellValueByColumnAndRow($column, 8, "QTE");
		$sheet->setCellValueByColumnAndRow($column, 9, '-'.$articles[$i]['uvc']);
		$sheet->setCellValueByColumnAndRow($column, 10, '-'.$articles[$i]['uvc']);
		$sheet->setCellValueByColumnAndRow($column, 11, str_replace('.',',',(-$articles[$i]['uvc']* $prixFac)));
		$column++;
	}
}

$writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
$writer->setDelimiter(';');
$writer->setEnclosure('');
$writer->setLineEnding("\r\n");
$writer->setSheetIndex(0);
$nbAvoir=($nbAvoir==0)?'':$nbAvoir+1;

$writer->save($uploadDir.'\AVCASSE_'.date('dmy').$nbAvoir.'.csv');


// maj db avec montant factures
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
					<?php
					if($nbFac==0){
						// si un seul fichier facture
						if(file_exists($uploadDir.'\FACTCASSE_'.date('dmy').'.csv')){
							echo '<a href="'.URL_UPLOAD.'\casse\FACTCASSE_'.date('dmy').'.csv" class="flash"><button type="button" class="button btn-plus">Télécharger</button></a>';
						}
					}else{
						echo '<button type="button" class="button btn-plus">Télécharger :<br>';

						for($i=1;$i<=$nbFac;$i++){
							if(file_exists($uploadDir.'\FACTCASSE_'.date('dmy').$i.'.csv')){
								echo '<a href="'.URL_UPLOAD.'\casse\FACTCASSE_'.date('dmy').$i.'.csv" class="flash">Fichier '.$i.'</a>';
								echo ($i==$nbFac) ? '' : ' - ' ;

							}
						}
						echo '</button>';

					}

					?>
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
					<?php
					if($nbAvoir==0){
						// si un seul fichier facture
						if(file_exists($uploadDir.'\AVCASSE_'.date('dmy').'.csv')){
							echo '<a href="'.URL_UPLOAD.'\casse\AVCASSE_'.date('dmy').'.csv" class="flash"><button type="button" class="button btn-plus">Télécharger</button></a>';
						}
					}else{
						echo '<button type="button" class="button btn-plus">Télécharger :<br>';

						for($i=1;$i<=$nbAvoir;$i++){
							if(file_exists($uploadDir.'\AVCASSE_'.date('dmy').$i.'.csv')){
								echo '<a href="'.URL_UPLOAD.'\casse\AVCASSE_'.date('dmy').$i.'.csv" class="flash">Fichier '.$i.'</a>';
								echo ($i==$nbAvoir) ? '' : ' - ' ;
							}
						}
						echo '</button>';

					}

					?>
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