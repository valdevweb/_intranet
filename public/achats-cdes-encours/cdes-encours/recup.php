<?php


foreach ($listInfos[$cdes['id']] as $key => $value){

if($listInfos[$cdes['id']][$key]['week_previ']!="" && $listInfos[$cdes['id']][$key]['week_previ']!=" "){
    // on écrase pour avoir la dernière semaine saisie
    $week=$listInfos[$cdes['id']][$key]['week_previ'];
}
$qte=$listInfos[$cdes['id']][$key]['qte_previ']+$qte;

if($listInfos[$cdes['id']][$key]['cmt']!="" && $listInfos[$cdes['id']][$key]['cmt']!=" "){
    $arrayCmt[]=date('d/m/y',strtotime($listInfos[$cdes['id']][$key]['date_insert'])). " : ". preg_replace("/\r|\n/", "",$listInfos[$cdes['id']][$key]['cmt']);
}

}

$sheet->setCellValue('u'.$row, $week);
$spreadsheet->getActiveSheet()->getStyle('u'.$row,)->getAlignment()->setWrapText(true);
if(	$qte!=0){
$sheet->setCellValue('v'.$row,$qte);
}