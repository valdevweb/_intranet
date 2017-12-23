<?php

require 'config/autoload.php';
echo $okko;



//$pdoUser=getWebUserLink();
//$pdoBt=getBTLink();



function sentTo($pdoBt, $slug){
	$req=$pdoBt->prepare('SELECT id FROM ldnames WHERE name= :name');
	$req->execute(array(
		':name'	=>$slug
	));
	if($ld=$req->fetch(PDO::FETCH_ASSOC))
		{
			$req=$pdoBt->prepare('SELECT mail FROM mail LEFT JOIN lk_mailing ON mail.id=lk_mailing.id_mail WHERE id_ld= :ld');
			$req->execute(array(
			':ld'	=>$ld[id]
		));
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}
}
$login=array();

$req=$pdoBt->prepare('SELECT btlec.id, btlec.login FROM btlec LEFT JOIN lk_user ON btlec.id=lk_user.id_btlec ');
$req->execute();
$result=$req->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $key => $value)
{
	$array = array('' => , );
}
	echo "<pre>";
	var_dump($result);
	echo '</pre>';

if($result){}





 ?>