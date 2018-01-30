<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}

//----------------------------------------------
// css dynamique
//----------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";




//------------------------------------------------------
//			DATA
//------------------------------------------------------
require '../../functions/global.fn.php';
require '../../functions/odr.fn.php';

$currentOdr=showCurrentOdr($pdoBt);
$nextOdr=showNextOdr($pdoBt);
function displayTable($data)
{
	$location=UPLOAD_DIR ."/odr/";
	$html='';
	foreach ($data as $d)
	{
		$start=new DateTime($d['startdate']);
		$start=$start->format('d-m-Y');
		$end=new DateTime($d['enddate']);
		$end=$end->format('d-m-Y');
		$link=createMultiLink($d['files'],$location,'<br>');
		$html.='<tr><td>'.$d['operation'].'</td><td>'.$d['brand'].'</td><td>'.$d['gt'].'</td><td>'.$start.'</td><td>'.$end.'</td><td>'.$link.'</td></tr>';
	}
return $html;
}


$currentOdrHtml=displayTable($currentOdr);
$nextOdrHtml=displayTable($nextOdr);


include('../view/_head-mig.php');
include('../view/_navbar.php');

// ------------------------------------------------------------------------------
include 'odr-display.ct.php';



include('../view/_footer.php');
