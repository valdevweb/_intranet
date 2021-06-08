<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}




//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
require_once '../../vendor/autoload.php';

require '../../Class/Db.php';

require '../../Class/CataDao.php';
require '../../Class/InfoLivDao.php';
require '../../Class/ArticleAchatsDao.php';
require '../../Class/FournisseursHelpers.php';
require '../../Class/Helpers.php';



// require_once '../../vendor/autoload.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoQlik=$db->getPdo('qlik');
$pdoDAchat=$db->getPdo('doc_achats');
$pdoFou=$db->getPdo('fournisseurs');


$inInfoLiv=1;

$cataDao= new CataDao($pdoQlik);
$infoLivDao=new infoLivDao($pdoDAchat);
$articleDao=new ArticleAchatsDao($pdoDAchat);



$listOpAVenir=$infoLivDao->getOpAVenir();


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Code op');
$sheet->setCellValue('b1', 'Nom opération');
$sheet->setCellValue('c1', 'Code catalogue');
$sheet->setCellValue('d1', 'Date de début');
$sheet->setCellValue('e1', 'Date de fin');
$sheet->setCellValue('f1', 'article');
$sheet->setCellValue('g1', 'dossier');
$sheet->setCellValue('h1', 'libellé');
$sheet->setCellValue('i1', 'ean');
$sheet->setCellValue('j1', 'marque');
$sheet->setCellValue('k1', 'reçu 2 lundi avant');
$sheet->setCellValue('l1', 'commentaire 2 lundi avant');
$sheet->setCellValue('m1', 'reçu 1 lundi avant');
$sheet->setCellValue('n1', 'commentaire 1 lundi avant');
$sheet->setCellValue('o1', 'article de remplacement');
$sheet->setCellValue('p1', 'ean article de remplacement');
$sheet->setCellValue('q1', 'art remplacement, reçu 2 lundi avant');
$sheet->setCellValue('r1', 'art remplacement, commentaire 2 lundi avant');
$sheet->setCellValue('s1', 'art remplacement, reçu 1 lundi avant');
$sheet->setCellValue('t1', 'art remplacement, commentaire 1 lundi avant');

$row=2;
foreach ($listOpAVenir as $key => $op) {
	$infoLiv=$infoLivDao->getInfoLivByOp($op['code_op']);
	if(!empty($infoLiv)){
		foreach ($infoLiv as $key => $info) {
			$sheet->setCellValue('A'.$row, $op['code_op']);
			$sheet->setCellValue('b'.$row, $op['operation']);
			$sheet->setCellValue('c'.$row, $op['code_cata']);
			$sheet->setCellValue('d'.$row, date('d/m/Y',strtotime($op['date_start'])));
			$sheet->setCellValue('e'.$row, date('d/m/Y',strtotime($op['date_end'])));
			$sheet->setCellValue('f'.$row, $info['article']);
			$sheet->setCellValue('g'.$row, $info['dossier']);
			$sheet->setCellValue('h'.$row, $info['libelle']);
			$sheet->setCellValue('i'.$row, $info['ean']);
			$sheet->setCellValue('j'.$row, $info['marque']);
			if (!empty($info['recu_deux'])) {
				$sheet->setCellValue('k'.$row, $info['recu_deux'].'%');
			}
			$sheet->setCellValue('l'.$row, $info['info_livraison_deux']);
			if (!empty($info['recu'])) {
				$sheet->setCellValue('m'.$row, $info['recu'].'%');
			}

			$sheet->setCellValue('n'.$row, $info['info_livraison']);
			$sheet->setCellValue('o'.$row, $info['article_remplace']);
			$sheet->setCellValue('p'.$row, $info['ean_remplace']);
			if (!empty($info['recu_deux_remplace'])) {
				$sheet->setCellValue('q'.$row, $info['recu_deux_remplace'].'%');
			}
			$sheet->setCellValue('r'.$row, $info['info_livraison_deux_remplace']);
			if (!empty($info['recu_remplace'])) {
				$sheet->setCellValue('s'.$row, $info['recu_remplace'].'%');
			}
			$sheet->setCellValue('t'.$row, $info['info_livraison_remplace']);
			$row++;
		}
	}


}
$highestRow = $sheet->getHighestRow();


$highestColumn = $sheet->getHighestColumn();
$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);


for ($i=0; $i < $highestColumnIndex ; $i++){
	$sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
}
$sheet->getStyle('I2:I'.$highestRow)
->getNumberFormat()
->setFormatCode(
	'0000000000000'
);

$sheet->getStyle('i:i')->getAlignment()->setHorizontal('right');
$sheet->getStyle('k:k')->getAlignment()->setHorizontal('center');
$sheet->getStyle('m:m')->getAlignment()->setHorizontal('center');
$sheet->getStyle('q:q')->getAlignment()->setHorizontal('center');
$sheet->getStyle('s:s')->getAlignment()->setHorizontal('center');


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="info-livraison.xlsx"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit();





