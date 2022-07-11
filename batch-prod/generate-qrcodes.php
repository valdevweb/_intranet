<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}


include 'config/config.inc.php';
include 'vendor/autoload.php';
include 'Class/Db.php';
include 'Class/CrudDao.php';

include 'vendor/phpqrcode/qrlib.php';


$db=new Db();

$pdoBt=$db->getPdo('btlec');

$crudDao=new CrudDao($pdoBt);

$parts=$crudDao->getAll('salon_2022');



foreach($parts as $part){
    $qrcodeData=10000+$part['id'];
    $qrcodeImg=md5($qrcodeData).'.png';
    $qrcodeFile=DIR_UPLOAD.'qrcodes\\'.$qrcodeImg;
    QRcode::png($qrcodeData, $qrcodeFile);

}
