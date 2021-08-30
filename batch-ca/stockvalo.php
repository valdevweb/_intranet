<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}


include 'config/config.inc.php';
include 'config/db-connect.php';

function exceptions_error_handler($severity, $message, $filename, $lineno) {
	throw new ErrorException($message, 0, $severity, $filename, $lineno);
}
set_error_handler('exceptions_error_handler');

function insertValo($pdoQlik, $valo, $date){
	$req=$pdoQlik->prepare("INSERT INTO statsstockvalo (date_stock, valo) VALUES (:date_stock, :valo)");
	$req->execute([
		':date_stock'=>$date->format('Y-m-d'),
		':valo'	=>$valo

	]);
	return $req->errorInfo();
}

$file=DIR_IMPORT_DUMP."StockValoJour.csv";
$row=0;


if (($handle = fopen($file, "r")) !== FALSE) {
	$errArr=[];
	while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
		if($row==0){
			$row++;
		}else{
			$date=$data[0];
			$valo=floatval($data[1]);
			echo $valo;
			echo "<br>";

			$excelStart=new DateTime('1899-12-30');
			if(!empty($date)){
				try{
					$dateInDatetime=clone $excelStart->modify('+ '.$date. ' day ');
					$result=insertValo($pdoQlik, $valo, $dateInDatetime);



				}catch(Exception $e){
					$errors[]="la date à la ligne ".$row. " n'est pas dans un format correct. <br>";
				}
			}


		}
	}
}