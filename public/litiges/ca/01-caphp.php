<?php

function getFinance($pdoQlik, $btlec, $year){
	$req=$pdoQlik->prepare("SELECT CA_Annuel FROM statsventesadh WHERE CodeBtlec= :btlec AND AnneeCA= :year");
	$req->execute(array(
		':btlec' =>$btlec,
		':year'	=>$year
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}


function getSumDeclare($pdoLitige,$galec,$year){
	$req=$pdoLitige->prepare("SELECT sum(valo) as sumValo FROM dossiers WHERE galec=:galec AND DATE_FORMAT(date_crea, '%Y')=:year");
	$req->execute([
		':galec'		=>$galec,
		':year'			=>$year
	]);
	return $req->fetch(PDO::FETCH_ASSOC);

}

function getSumDeclareOcc($pdoLitige,$galec,$year){
	$req=$pdoLitige->prepare("SELECT sum(valo) as sumValo FROM dossiers WHERE galec=:galec AND DATE_FORMAT(date_crea, '%Y')=:year AND occasion=1");
	$req->execute([
		':galec'		=>$galec,
		':year'			=>$year
	]);
	return $req->fetch(PDO::FETCH_ASSOC);

}
function getMtMag($pdoLitige,$galec,$year){
	$req=$pdoLitige->prepare("SELECT sum(mt_mag) as sumMtMag FROM dossiers WHERE galec LIKE :galec AND DATE_FORMAT(date_crea, '%Y')=:year");
	$req->execute([
		':galec'		=>$galec,
		':year'			=>$year
	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}

function getMtMagOcc($pdoLitige,$galec,$year){
	$req=$pdoLitige->prepare("SELECT sum(mt_mag) as sumMtMag FROM dossiers WHERE galec LIKE :galec AND DATE_FORMAT(date_crea, '%Y')=:year AND occasion=1");
	$req->execute([
		':galec'		=>$galec,
		':year'			=>$year
	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}


function getCoutTotalYear($pdoLitige,$galec,$year){
	$req=$pdoLitige->prepare("SELECT sum(mt_mag) as mtMag, sum(mt_assur) as mtassur, sum(mt_transp) as mttransp, sum(mt_fourn) as mtfourn FROM dossiers WHERE galec=:galec AND DATE_FORMAT(date_crea, '%Y')=:year");
	$req->execute([
		':galec'		=>$galec,
		':year'			=>$year
	]);
	return $req->fetch(PDO::FETCH_ASSOC);

}

function getCoutTotalYearOcc($pdoLitige,$galec,$year){
	$req=$pdoLitige->prepare("SELECT sum(mt_mag) as mtMag, sum(mt_assur) as mtassur, sum(mt_transp) as mttransp, sum(mt_fourn) as mtfourn FROM dossiers WHERE galec=:galec AND DATE_FORMAT(date_crea, '%Y')=:year AND occasion=1");
	$req->execute([
		':galec'		=>$galec,
		':year'			=>$year
	]);
	return $req->fetch(PDO::FETCH_ASSOC);

}

$yearN=date('Y');
$yearNUn= date("Y",strtotime("-1 year"));
$yearNDeux= date("Y",strtotime("-2 year"));

$financeN=getFinance($pdoQlik,$codeBt,$yearN);
$financeNUn=getFinance($pdoQlik,$codeBt,$yearNUn);
$financeNDeux=getFinance($pdoQlik,$codeBt,$yearNDeux);

$totalReclameN=getSumDeclare($pdoLitige,$codeGalec,$yearN);
$totalReclameNUn=getSumDeclare($pdoLitige,$codeGalec,$yearNUn);
$totalReclameNDeux=getSumDeclare($pdoLitige,$codeGalec,$yearNDeux);

$occReclameN=getSumDeclareOcc($pdoLitige,$codeGalec,$yearN);
$occReclameNUn=getSumDeclareOcc($pdoLitige,$codeGalec,$yearNUn);
$occReclameNDeux=getSumDeclareOcc($pdoLitige,$codeGalec,$yearNDeux);

$horsOccReclameN= $totalReclameN['sumValo']-$occReclameN['sumValo'];
$horsOccReclameNUn=  $totalReclameNUn['sumValo']-$occReclameNUn['sumValo'];
$horsOccReclameNDeux=  $totalReclameNDeux['sumValo']-$occReclameNDeux['sumValo'];

$totalRembourseN=getMtMag($pdoLitige,$codeGalec,$yearN);
$totalRembourseNUn=getMtMag($pdoLitige,$codeGalec,$yearNUn);
$totalRembourseNDeux=getMtMag($pdoLitige,$codeGalec,$yearNDeux);


$occRembourseN=getMtMagOcc($pdoLitige,$codeGalec,$yearN);
$occRembourseNUn=getMtMagOcc($pdoLitige,$codeGalec,$yearNUn);
$occRembourseNDeux=getMtMagOcc($pdoLitige,$codeGalec,$yearNDeux);


$horsOccRembourseN=$totalRembourseN['sumMtMag']-$occRembourseN['sumMtMag'];
$horsOccRembourseNUn=$totalRembourseNUn['sumMtMag']-$occRembourseNUn['sumMtMag'];
$horsOccRembourseNDeux=$totalRembourseNDeux['sumMtMag']-$occRembourseNDeux['sumMtMag'];


$totalCoutN=getCoutTotalYear($pdoLitige,$codeGalec,$yearN);
$totalCoutNUn=getCoutTotalYear($pdoLitige,$codeGalec,$yearNUn);
$totalCoutNDeux=getCoutTotalYear($pdoLitige,$codeGalec,$yearNDeux);

$totalCoutN=$totalCoutN['mtMag']+$totalCoutN['mtfourn']+$totalCoutN['mttransp']+$totalCoutN['mtassur'];
$totalCoutNUn=$totalCoutNUn['mtMag']+$totalCoutNUn['mtfourn']+$totalCoutNUn['mttransp']+$totalCoutNUn['mtassur'];
$totalCoutNDeux=$totalCoutNDeux['mtMag']+$totalCoutNDeux['mtfourn']+$totalCoutNDeux['mttransp']+$totalCoutNDeux['mtassur'];


$occCoutN=getCoutTotalYearOcc($pdoLitige,$codeGalec,$yearN);
$occCoutNUn=getCoutTotalYearOcc($pdoLitige,$codeGalec,$yearNUn);
$occCoutNDeux=getCoutTotalYearOcc($pdoLitige,$codeGalec,$yearNDeux);

$occCoutN=$occCoutN['mtMag']+$occCoutN['mtfourn']+$occCoutN['mttransp']+$occCoutN['mtassur'];
$occCoutNUn=$occCoutNUn['mtMag']+$occCoutNUn['mtfourn']+$occCoutNUn['mttransp']+$occCoutNUn['mtassur'];
$occCoutNDeux=$occCoutNDeux['mtMag']+$occCoutNDeux['mtfourn']+$occCoutNDeux['mttransp']+$occCoutNDeux['mtassur'];


$horsOccCoutN=$totalCoutN-$occCoutN;
$horsOccCoutNUn=$totalCoutNUn-$occCoutNUn;
$horsOccCoutNDeux=$totalCoutNDeux-$occCoutNDeux;
