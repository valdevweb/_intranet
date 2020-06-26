<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	echo "pas de variable session";

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



//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// addRecord($pdoStat,basename(__file__),'consultation', "fichiers d'info du service achats", 101);

function addImport($pdoBt, $filename){
	$req=$pdoBt->prepare("INSERT INTO occ_import_excel( filename, date_import, id_web_user) VALUES  (:filename, :date_import, :id_web_user)");
	$req->execute([
		':filename'		=> $filename,
		':date_import'		=>date('Y-m-d H:i:s'),
		':id_web_user'		=>$_SESSION['id_web_user']
	]);

	return $pdoBt->lastInsertId();
}

function addPalette($pdoBt, $palette){
	$req=$pdoBt->prepare("INSERT INTO occ_palettes (palette, statut, import, date_crea) VALUES  (:palette, :statut, :import, :date_crea)");
	$req->execute([
		':palette'		=>$palette,
		':statut'		=>1,
		':import'		=>1,
		':date_crea'	=>date('Y-m-d H:i:s'),
	]);
	return $pdoBt->lastInsertId();
}
function insertArticle($pdoBt, $idPalette, $idImport, $designation,$ean, $qte, $pa, $pvc, $marge)	{
	$req=$pdoBt->prepare("INSERT INTO occ_articles (id_palette, id_import,  designation, ean, quantite, pa, pvc, marge, date_insert) VALUES  (:id_palette, :id_import, :designation, :ean, :quantite, :pa, :pvc, :marge, :date_insert)");
	$req->execute([
		':id_palette'	=>$idPalette,
		':id_import'	=>$idImport,
		':designation'	=>$designation,
		':ean'	=>$ean,
		':quantite'	=>$qte,
		':pa'	=>$pa,
		':pvc'	=>$pvc,
		':marge'	=>$marge,
		':date_insert'	=>date('Y-m-d H:i:s'),
	]);
	return $pdoBt->lastInsertId();
}

 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// import des connées excel :
// table occ_import excel => recup id
// table palette => 1 pour import
// table occ_article => recup les id palette.....

//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
if(isset($_POST['send']))
{
	if($_FILES['file']['error']===0)
	{
		$uploadDir=DIR_UPLOAD."\\excel\\";
		$filename=new SplFileInfo($_FILES['file']['name']);

		if(!move_uploaded_file($_FILES['file']['tmp_name'],$uploadDir.$filename))
		{
			$errors[]="erreur d'upload, le fichier n'a pas pu être enregistré";
		}


		if(count($errors)==0){

			$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
			$reader->setReadDataOnly(TRUE);

			$path=DIR_UPLOAD."\\excel\\";
			$fxls=$path.$filename;
			$spreadsheet = $reader->load($fxls);
			$worksheet = $spreadsheet->getActiveSheet();
			$highestRow = $worksheet->getHighestRow();
			// 1 ajout fichier dans table imporyt et recup id
			$idImport=addImport($pdoBt, $filename);

			$firstPalette=true;
			for ($row = 2; $row <$highestRow; ++$row){
					// $worksheet->unmergeCells('A29:C35');
				$ean=$worksheet->getCell('A' . $row)->getValue();
				$libelle=$worksheet->getCell('B' . $row)->getValue();
				$qte=$worksheet->getCell('C' . $row)->getValue();
				$palette=$worksheet->getCell('D' . $row)->getValue();
				$arPalette[]=$palette;
				$pa=$worksheet->getCell('E' . $row)->getValue();
				$pvc=$worksheet->getCell('F' . $row)->getValue();
				$taux=$worksheet->getCell('G' . $row)->getValue();
				$excelData[]=
				[
					'ean'	=>$ean,
					'libelle'	=>$libelle,
					'qte'		=>$qte,
					'palette'	=>$palette,
					'pa'		=>$pa,
					'pvc'		=>$pvc,
					'taux'		=>round(($taux*100),2)
				];


			}


					//ajout dans table palete et recup id et créa tableau palette

			$arPalette=array_unique($arPalette);
			$arPalette = array_values($arPalette);

			for($i=0;$i<count($arPalette); $i++){
				$idPalette=addPalette($pdoBt, $arPalette[$i]);
				$newArPalette[$arPalette[$i]]=$idPalette;

			}


			foreach ($excelData as $key => $data) {
				insertArticle($pdoBt, $newArPalette[$data['palette']], $idImport, $data['libelle'],$data['ean'], $data['qte'], $data['pa'], $data['pvc'], $data['taux']);
			}

		}

		$success[]="le fichier ". $filename." a été traité avec succès  " ;

	}


}


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
	<h1 class="text-main-blue py-5 ">Importation de fichiers </h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>

	<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" enctype="multipart/form-data">


		<div class="row">
			<div class="col">
				<div id="file-upload">
					<fieldset>
						<p class="pt-2">Document :</p>
						<div class="form-group">
							<p><input type="file" name="file" class='form-control-file'></p>
						</div>
					</fieldset>
				</div>
			</div>
			<!-- <div class="col"></div> -->
		</div>
		<div class="row pt-4">
			<div class="col">
				<p class="text-right">
					<button type="submit" id="submit" class="btn btn-primary" name="send">Envoyer</button>
				</p>
			</div>
			<!-- <div class="col"></div> -->

		</div>

	</form>

	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>