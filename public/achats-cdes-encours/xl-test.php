<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}

$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";


require '../../Class/Db.php';
require '../../Class/Helpers.php';

require '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
function getNameFromNumber($num) {
	$numeric = $num % 26;
	$letter = chr(65 + $numeric);
	$num2 = intval($num / 26);
	if ($num2 > 0) {
		return getNameFromNumber($num2 - 1) . $letter;
	} else {
		return $letter;
	}
}

$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
$reader->setReadDataOnly(TRUE);
$excelStart=new DateTimeImmutable('1899-12-30');


$fxls=DIR_UPLOAD."test.xlsx";
$spreadsheet = $reader->load($fxls);
$worksheet = $spreadsheet->getActiveSheet();
$highestRow = $worksheet->getHighestRow();


			// 1er boucle pour tester la validité des valeurs, on veut qu'aucune ligne ne soit insérée en base de donnée si le fichier contient des erreurs
for ($row = 2; $row < $highestRow; ++$row){

				// 1er colonne de donnée = v soit 22
				// col 1 =v cad 22  // ordre : id qte date cmt
	for ($i=0; $i <6 ; $i++) {
		$colId=21+($i*4)+0;
		$colIdStr=getNameFromNumber($colId);
		$colQte=21+($i*4)+1;
		$colQteStr=getNameFromNumber($colQte);
		$colDate=21+($i*4)+2;
		$colDateStr=getNameFromNumber($colDate);
		$colCmt=21+($i*4)+3;
		$colCmtStr=getNameFromNumber($colCmt);

		$thisId=$worksheet->getCell($colIdStr . $row)->getValue();
		$thisDate=$worksheet->getCell($colDateStr . $row)->getValue();
		$thisQte=$worksheet->getCell($colQteStr . $row)->getValue();
		$thisCmt=$worksheet->getCell($colCmtStr . $row)->getValue();
					// echo "infos ligne" .$row. " : id ".$thisId." date ".$thisDate. " qte ".$thisQte. " cmt ".$thisCmt;
					// echo "<br>";


		if($thisId=="" && $thisCmt=="" && $thisDate=="" && $thisCmt==""){
						// echo "vide on fait rien";
						// echo "<br>";
						// on fait rien
		}elseif($thisId!="" && $thisCmt=="" && $thisDate=="" && $thisCmt==""){
						// echo "vide avec id, on supprime";
						// echo "<br>";

						// on supprimer la ligne
			// $cdesAchatDao->deleteInfo($thisId);
		}else{
						// update ou insert
			if(!empty($thisQte) && !is_numeric($thisQte)){
				echo "la quantité, " .$qte. ", à la ligne ".$row. " n'est pas dans un format correct. <br>" ;
				exit;
			}

			if($thisDate !=""){
				echo $thisDate;
				echo "<br>";
				try{
					$thisDate=clone $excelStart->modify('+ '.$thisDate. ' day ');
					$thisDate=$thisDate->format("Y-m-d");
							echo $thisDate;
							echo "<br>";
				}catch(Exception $e){
					echo "la date à la ligne ".$row. " n'est pas dans un format correct. <br>";
					exit;
				}
				// $thisDate=Helpers::strToDate($thisDate);
				// if(!empty($thisDate)){
				// 	$thisDate=$thisDate->format("Y-m-d");
				// 	echo $thisDate;
				// 	echo "<br>";

				// }else{
				// 	$thisDate=null;
				// 	echo "errure";
				// 	echo "<br>";

				// }

			}else{
				$thisDate=null;
			}
			$thisQte=(empty($thisQte))?null:$thisQte;

			if($thisId==""){
						// nouvelles données
				$idDetail=$worksheet->getCell('a' . $row)->getValue();

				if ($idDetail=="") {
								// echo "iddetail null ligne ".$row;
				}else{

					// $cdesAchatDao->insertInfos($idImport, $idDetail,$thisDate, $thisQte, $thisCmt);
				}

			}else{
							// update donnees
											// echo "maj info row ".$row;
							// echo "<br>";
				// $cdesAchatDao->updateInfo($thisId, $thisDate, $thisQte,$thisCmt);

			}

		}
	}


}



//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div id="container" class="container">
	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue">Main title</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>
</div>

<?php
require '../view/_footer-bt.php';
?>














