<?php


function buildHeader($expediteur,$boundary){
	$headers = "From: $expediteur \n";
    $headers .= "Reply-to: $expediteur \n";
    $headers .= "X-Priority: 1 \n";
    $headers .= "MIME-Version: 1.0 \n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\" \n";
    $headers .= " \n";
return $headers;
}


function buildBody($boundary,$link,$bodyContent, $lastId)
{
	if(!empty($link))
	{
		$link ="<a href='". $link."'>Fichier joint </a>" ;

	}

	$body  = "--$boundary \n";
	$body .= "Content-Type: text/html; charset=\"utf-8\" \n";
	$body .= "Content-Transfer-Encoding:8bit \n";
	$body .= "\n";
	$body .= "Bonjour,<br/>";
	$body .= "<br/>";
	$body .= "\n";
	$body .= "<br/>";
	$body .= $bodyContent;
	$body .= "\n";
	$body .= "Objet du message : ";
	$body .= $_POST['objet'];
	$body .= "\n";
	$body .= "<br/></br>";
	//$body .= $_POST['msg']."\r\n";
	$body .= "<br/><br>";
	$body .="Cliquez <a href='http://172.30.92.53/". VERSION ."btlecest/index.php?".$lastId."'>ici pour consulter le message</a>" ;
	$body .= "<br/><br><br>";
	$body.=$link;
	// $body .="<a href='". $link."'>Fichier joint </a>" ;
	$body .= "\n";
	$body .= "--$boundary \n";
	//$body .= "Content-Type: $type; name=\"$filename\" \n";
	//$body .= "Content-Transfer-Encoding: base64 \n";
	//$body .= "Content-Disposition: attachment; filename=\"$filename\" \n";
	$body .= "\n";
// 	$body .= $data=chunk_split( base64_encode(file_get_contents($filename)) )."\n";
	$body .= "\n";
	$body .= "--".$boundary."--";

	return $body;
}


 ?>



