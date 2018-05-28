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

    $content="Bonjour, <br><br><br>";
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
    $htmlContent=$content;

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
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
		if($docDate > $deadLine)
		{
			$fileList[]="la " . $dbFile['category'];
		}
	}
}
if(count($fileList)!=0)
{
	$mailingList="btlecest.portailweb.gazettes@btlec.fr";
	mb_internal_encoding('UTF-8');
	$subject="Portail BTLec Est - vos infos du jour";
	$subject = mb_encode_mimeheader($subject);

	mailUpdateDoc($mailingList,$subject,$fileList);
}
