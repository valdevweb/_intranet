<?php


function sendMail($mailingList,$subject,$tplLocation,$contentOne,$contentTwo,$link)
{
	$tpl = file_get_contents($tplLocation);
	$tpl=str_replace('{CONTENT1}',$contentOne,$tpl);
	$tpl=str_replace('{CONTENT2}',$contentTwo,$tpl);
	$tpl=str_replace('{LINK}',$link,$tpl);


	$htmlContent=$tpl;
// Set content-type header for sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// Additional headers
	$headers .= 'From: ne_pas_repondre@btlec.fr>' . "\r\n";
	$headers .= 'Cc: ' . "\r\n";
	$headers .= 'Bcc:' . "\r\n";

	if(mail($mailingList,$subject,$htmlContent,$headers))
	{
		return true;
	}
	else
	{
		return false;
	}

}


//exmeples
$tplForBtlec="../mail/new_mag_msg.tpl.html";
$tplForMag="../mail/ar_mag.tpl.html";
$objBt="PORTAIL BTLec - nouvelle demande magasin";
$objMag="PORTAIL BTLec - demande envoyée";
//$objMag = utf8_decode($objMag);
//$objMag = mb_encode_mimeheader($objMag,"UTF-8");
mb_internal_encoding('UTF-8');
$objMag = mb_encode_mimeheader($objMag);

$magName=$_SESSION['nom'];

// listId récupéré qd insert données dans db
	$req->fetch(PDO::FETCH_ASSOC);
	return $db->lastInsertId();
$link="Cliquez <a href='http://172.30.92.53/". VERSION ."btlecest/index.php?".$lastId."'>ici pour consulter le message</a>";
