<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}

require_once '../../vendor/autoload.php';

include '../../config/db-connect.php';
require_once '../../Class/MagHelpers.php';
require_once '../../Class/UserHelpers.php';


//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
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

$styleArray = [
	'font' => [
		'bold' => true,
		'color'=>['rgb'=>'FFFFFF']
	],
	'borders' => [
		'top' => [
			'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
		],
	],
	'fill' => [
		'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
		'color' => [
			'rgb' => '0075BC',
		],
	],
];


//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

if(isset($_GET['nofilter']) && $_GET["nofilter"]==1){

	$query="SELECT mag.*,sca3.*,web_users.users.login FROM mag LEFT JOIN sca3 ON mag.id=sca3.btlec_sca LEFT JOIN web_users.users ON mag.galec=web_users.users.galec  GROUP BY mag.id";
}else{

	$query=$_SESSION['mag_filters']['query'];
}
$req=$pdoMag->query($query);
$magList=$req->fetchAll(PDO::FETCH_ASSOC);

$listCentrale=MagHelpers::getListCentrale($pdoMag);
$listBackOffice=MagHelpers::getListBackOffice($pdoMag);
$listCm=UserHelpers::getListUserByService($pdoUser,17);


$fields = array_keys($magList[0]);
$ignoredFields=['id_type','closed', 'date_update', 'pole_sav', 'iban', 'bic', 'rum', 'adh_payeur', 'absent', 'id_cm_intern', 'date_insert', 'id_sca', 'btlec_sca', 'galec_sca', 'deno_sca', 'tel_sca', 'fax_sca', 'surface_sca', 'adherent_sca', 'galec_old', 'date_sortie', 'raison_sociale', 'date_resiliation', 'date_adhesion', 'centrale', 'date_update', 'ad1', 'ad2', 'cp', 'ville' ];
$centraleFields=['centrale_sca', 'centrale_doris', 'centrale_smiley'];
$ignoredSize=sizeOf($ignoredFields);
$realFieldsSize=(count($fields)-$ignoredSize);
$lastCol=getNameFromNumber($realFieldsSize);



use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
//  index des colonnes excel => se désynchrnise de l'index de colonnes du tableau de résultat puisqu'on n'affiche pas certains champs de résultats ($ignoredFields)
$iCol=0;
for($i=0;$i<sizeOf($fields);$i++){
	$columnLetter = getNameFromNumber($iCol);
		// on n'affiche pas la colonne id_type
	if(!in_array($fields[$i],$ignoredFields)){
		if($fields[$i]=="date_ouv"){
			$sheet->setCellValue($columnLetter.'1', 'date_ouverture_gessica');
		}elseif($fields[$i]=="date_fermeture_gessica"){
			$sheet->setCellValue($columnLetter.'1', 'date_fermeture_gessica');

		}else{
			$sheet->setCellValue($columnLetter.'1', $fields[$i]);
		}
		$iCol++;
	}

}




// echo $lastCol;
// champs spécifique :
// centrale
// id_cm_web_user

$row=2;

foreach ($magList as $key => $mag){
	$iCol=0;
	for($i=0;$i<sizeOf($fields);$i++){
		$columnLetter = getNameFromNumber($iCol);
		if(!in_array($fields[$i],$ignoredFields)){
			if(in_array($fields[$i],$centraleFields)){
				// on vérifie que l'index du tableau existe car si à 0 renvoie une erreur
				if(isset($listCentrale[$mag[$fields[$i]]])){
					$sheet->setCellValue($columnLetter.$row, $listCentrale[$mag[$fields[$i]]]);
				}
			}
			elseif($fields[$i]=='backoffice'){
				if(isset( $listBackOffice[$mag[$fields[$i]]])){
					$sheet->setCellValue($columnLetter.$row, $listBackOffice[$mag[$fields[$i]]]);
				}

			}elseif($fields[$i]=='id_cm_web_user'){
				if(isset($listCm[$mag[$fields[$i]]])){
					$sheet->setCellValue($columnLetter.$row, $listCm[$mag[$fields[$i]]]);
				}else{
					$sheet->setCellValue($columnLetter.$row, $mag[$fields[$i]]);

				}

			}
			else{
				$sheet->setCellValue($columnLetter.$row, $mag[$fields[$i]]);
			}
			$iCol++;

		}
	}
	$row++;
}
$spreadsheet->getActiveSheet()->getStyle('A1:'.$lastCol.'1')->applyFromArray($styleArray);

 // dimensionnement des colnes

for ($i=0; $i <$realFieldsSize ; $i++){
	$columnLetter = getNameFromNumber($i);
	$sheet->getColumnDimension($columnLetter)->setAutoSize(true);
}
$sheet->setTitle('base mag');
	// echo "<pre>";
	// print_r($magList);
	// echo '</pre>';


// pour lancer le téléchargement sur le poste client
$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="export-basemag.xlsx"');
$writer->save("php://output");
exit;



?>