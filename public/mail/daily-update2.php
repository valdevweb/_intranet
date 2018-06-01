<?php
//------------------------------------------------------
//			INFOS
//------------------------------------------------------
/*
Page lancée par tâche planifiée à 18h du lundi au vendredi
pas d'autoload car :
- include impossible (doc root point sur le c)
- pas de démarrage de session

 */
//------------------------------------------------------
//			CONNEXION A LA DB
//------------------------------------------------------
$path=dirname(__FILE__);
if (preg_match('/_btlecest/', $path))
{
	$database='_btlec';
}
else
{
	$database='btlec';
}
$host='localhost';
$username='sql';
$pwd='User19092017+';

try {
	$pdoBt=new PDO("mysql:host=$host;dbname=$database", $username, $pwd);
}

catch(Exception $e)
{
	die('Erreur : '.$e->getMessage());
}

//------------------------------------------------------
//			fn mail
//------------------------------------------------------
function mailUpdateDoc($mailingList,$subject,$fileList)
{

	// on génère une frontière
  $boundary = '-----=' . md5( uniqid ( rand() ) );
   $file_id  = md5( uniqid ( rand() ) ) . $_SERVER['SERVER_NAME'];


	$img='bt300.jpg';
	$fp =fopen($img,'rb');
	$read=fread($fp,filesize($img));
	fclose($fp);
	$content_encode=chunk_split(base64_encode($read));


  $content  = "Ceci est un message au format MIME 1.0 multipart/mixed.\n\n";
  $content .= "--" . $boundary . "\n";
  $content .= "Content-Type: text/plain; charset=\"iso-8859-1\"\n";
  $content .= "Content-Transfer-Encoding: 8bit\n\n";

	$content="<h1>Bonjour,</h1> <br><br><br>";
    $content.="Aujourd'hui, sur <a href='http://172.30.92.53/btlecest'>votre portail</a>, vous trouverez : <br>";
    $content .="<ul>";
    foreach ($fileList as $file) {
        $content .= "<li>";
        $content .=$file;
        $content .= "</li>";
    }
    $content .="</ul>";
    $content .="<br><br>";
    $content .="Cordialement,<br>";
    $content .= "BTLec EST - Portail";
     $content .= "<img src=\"cid:$file_id\" alt=\"le fichier demandé\"><br>";
   $content .= "--" . $boundary . "\n";
  $content .= "Content-Type: image/jpg; name=\"bt300.jpg\"\n";
  $content .= "Content-Transfer-Encoding: base64\n";
  // mettez inline au lieu de attachment
  // pour que l'image s'affiche dans l'email
  $content .= "Content-Disposition: attachment; filename=\"bt300.gif\"\n\n";
  $content .= $content_encode . "\n";
  $content .= "\n\n";
  $content .= "--" . $boundary . "--\n";

    $htmlContent=$content;

    $headers = "MIME-Version: 1.0" . "\r\n";
	 $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"";


    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: ne_pas_repondre@btlec.fr>' . "\r\n";
    $headers .= 'Cc: ' . "\r\n";
    $headers .= 'Bcc: valerie.montusclat@btlec.fr' . "\r\n";

    if(mail($mailingList,$subject,$htmlContent,$headers))
    {
        return true;
    }
    else
    {
        return false;
    }

}

//------------------------------------------------------
//			INITIALISATION
//------------------------------------------------------
//liste des fichiers mis à jour depuis hier 18h
$fileList=array();
$deadLine=new DateTime('yesterday 18:00:00');

//------------------------------------------------------
//			OPPORTUNITES
//------------------------------------------------------
$opp="D:\www\intranet\opportunites\index.html";
if (file_exists($opp))
{
	$opp=date ('Y-m-d H:i:s', filemtime($opp));
	$majOpp=new DateTime($opp);
	if($majOpp > $deadLine)
	{
		$fileList[]="de nouvelles alertes promos";
	}

}

//------------------------------------------------------
//			DOCUMENTS
//------------------------------------------------------
$req=$pdoBt->prepare("SELECT * FROM documents");
$req->execute();
$files=$req->fetchAll(PDO::FETCH_ASSOC);
foreach ($files as $dbFile) {
	$docDate=new DateTime($dbFile['date_modif']);
	if($docDate > $deadLine)
	{
		//si résultat GFK
		if($dbFile['code']==5)
		{
			$fileList[]="les nouveaux " . $dbFile['type'];
		}
		else
		{
		$fileList[]=$dbFile['type'];
		}
	}
}
//------------------------------------------------------
//			GAZETTES
//------------------------------------------------------
$req=$pdoBt->prepare("SELECT * FROM gazette");
$req->execute();
$files=$req->fetchAll(PDO::FETCH_ASSOC);
foreach ($files as $dbFile)
{
	if($dbFile['date_modif'] !="")
	{
		$docDate=new DateTime($dbFile['date_modif']);
		$flag=0;
		if($docDate > $deadLine)
		{
			$flag++;
		}
	}
}
// si on a au moins une gazete, on ajoute la gazette à la liste mais on ne l'ajoute qu'une fois
if($flag >=1)
	{
			$fileList[]="la " . $dbFile['category'];
	}

//------------------------------------------------------
//			si list fichiers non vide => envoi mail
//------------------------------------------------------

if(count($fileList)!=0)
{
	$mailingList="valerie.montusclat@btlec.fr";
	// $mailingList="btlecest.portailweb.gazettes@btlec.fr";
	mb_internal_encoding('UTF-8');
	$subject="Portail BTLec Est - vos infos du jour";
	$subject = mb_encode_mimeheader($subject);

	mailUpdateDoc($mailingList,$subject,$fileList);
}
