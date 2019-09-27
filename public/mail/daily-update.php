<style type="text/css">
	h1{
		color:  blue;
	}
</style>


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
//			date
//------------------------------------------------------
$jours=array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
$months=array("","janvier", "février","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","décembre");

$today= $jours[date('w')] .' '. date('d'). ' ' . $months[date('n')] . ' '. date('Y') ;


//------------------------------------------------------
//			fn mail
//------------------------------------------------------
function mailUpdateDoc($mailingList,$subject,$fileList, $today)
{
	$content="<html><body style='font-family: helvetica, arial, sans-serif; width :900px;'>";
	$content.="<table cellspacing='0' cellpadding='0'><tr><td style='width:20px;background-color:#90caf9;'></td><td style='width:20px;background-color:#90caf9;'></td><td style='width:800px; background-color:#90caf9;'>";
	$content.="<br><p style='font-style:italic; text-align:right'>".$today . "</p><br>";
	$content.="</td><td style='width:20px;background-color:#90caf9;'></td></tr><tr><td style='background-color:#e3f2fd;'></td><td colspan='2' style='background-color:#e3f2fd;'>";
	$content.="<br><h2 style='font-weight:normal'>Bonjour,</h2><br>";
    $content.="<p>Aujourd'hui, sur <a href='http://172.30.92.53/btlecest' target='_blank'>votre portail</a>, vous trouverez : </p><br>";
	$content.="</td><td style='width:20px;background-color:#e3f2fd;'></td></tr><tr><td style='background-color:#e3f2fd;'></td><td style='background-color:#e3f2fd;'></td><td style='background-color:#90caf9;'>";
	$content.="<div style = 'padding : 10px;'>";
    $content .="<ul style='list-style-type:circle'> ";
    foreach ($fileList as $file) {
        $content .= "<li>";
        $content .=$file;
        $content .= "</li>";
    }
    $content .="</ul>";
    $content .="</div><br><br>";
    $content .="</td><td style='width:20px;background-color:#e3f2fd;'></td></tr><tr><td style='background-color:#e3f2fd;'></td><td colspan='2' style='background-color:#e3f2fd;'>";
    $content .="<br><p>Cordialement,</p>";
    $content .="<p><img src='http://172.30.92.53/_btlecest/public/mail/logo.png' width='180px' height='60px'></p>";
    $content .= "<p style='color: #f57c00'>BTLec EST - Portail</p><br>";
    $content .="</td><td style='background-color:#e3f2fd;'></td></tr></table>";
	$content .="</body></html>";
    $htmlContent=$content;

    $headers = "MIME-Version: 1.0" . "\r\n";
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
	// echo "<pre>";
	// var_dump($deadLine);
	// echo '</pre>';

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
//on récupère tous les documents de la base
//on compare la date du jour avec la date de modif (=date d'upload)
$req=$pdoBt->prepare("SELECT * FROM documents");
$req->execute();
$files=$req->fetchAll(PDO::FETCH_ASSOC);
if($files>=1)
{
	foreach ($files as $dbFile) {
		$docDate=new DateTime($dbFile['date_modif']);
		if($docDate > $deadLine)
		{
			//si résultat GFK
			if($dbFile['id_doc_type']==5)
			{
				$fileList[]="les nouveaux " . $dbFile['type'];
			}
			elseif($dbFile['id_doc_type']==9)
			{
				$fileList[]="le " . $dbFile['type'] ." ". $dbFile['name'];
			}
			elseif($dbFile['id_doc_type']==10)
			{
				$fileList[]="la " . $dbFile['type'];
			}
			elseif($dbFile['id_doc_type']==7)
			{
				$fileList[]="les " . $dbFile['type'];
			}
			elseif($dbFile['id_doc_type']==6 || $dbFile['id_doc_type']==11)
			{
				$fileList[]="le " . $dbFile['type'];
			}
			elseif($dbFile['id_doc_type']==4)
			{
				$fileList[]= $dbFile['type'];
			}
			else
			{
			$fileList[]=$dbFile['type'];
			}
		}
	}
}
//------------------------------------------------------
//			GAZETTES
//------------------------------------------------------
// pour la gazette normal et la gazette speciale, on prend le champ date
// gazette quotidienne => date = date sélectionnée par la personne donc normalement date du jour (si on a modifié une ancienne gazette, on ne veut pas l'afficher)
// gazette spéciale => date de dépot ( pas de champ date pour la modifier dans le formulaire)
$req=$pdoBt->prepare("SELECT * FROM gazette WHERE date= :today AND (id_doc_type = 1 OR id_doc_type = 8)");
$req->execute(array(
	':today'	=> date('Y-m-d')
));
$files=$req->fetchAll(PDO::FETCH_ASSOC);
if($files>=1)
{
	foreach ($files as $g)
	{
		$fileList[]="la " . $g['category'];
	}
}

// pour la gazette appro, c'est la date de modif qu'il faut prendre en compte (date de dépot)
$req=$pdoBt->prepare("SELECT * FROM gazette WHERE id_doc_type = 2 AND DATE_FORMAT(date_modif, '%Y-%m-%d')= :today");
$req->execute(array(
	':today'	=> date('Y-m-d')
));
$files=$req->fetch(PDO::FETCH_ASSOC);
if($files>=1)
{
	$filename=explode('.',$files['file']);
	$filename=$filename[0];
	$fileList[]="la " . $files['category'] . " - " .$filename;
}
//------------------------------------------------------
//			DORIS
//------------------------------------------------------

/*--------------------------------------------*/
/*     recup date, centrale du nom de fichier */
/*--------------------------------------------*/

function fileInfos($explodedFilename)
{
	$strangeMonth=array('janv'=>1 ,'févr'=>2 ,'mars'=>3 ,'avr'=>4 ,'mai'=>5 ,'juin'=>6 ,'juil'=>7 ,'aout'=>8 ,	'sept'=>9 ,	'oct'=>10,'nov'=>11,'déc'=>12);
	$dateStr=$explodedFilename[1];
	$exDate=explode(' ',$dateStr);

	$monthStr=trim($exDate[1]);
	$monthStr=str_replace('.','',$monthStr);
	//quand existe pas ??
	$year=trim($exDate[2]);
	$centrale=$explodedFilename[2];
	$arrCentrale=explode('.',$centrale);
	$centrale=$arrCentrale[0];
	//quand pas mois
	$month=$strangeMonth[$monthStr];
	$day=1;
	$dorisDate=new DateTime($year.'-'.$month.'-'.$day);
	return array($dorisDate,$centrale);

}






function addDorisToDb($pdoBt,$filename,$dorisDate,$centrale){
	$req=$pdoBt->prepare('INSERT INTO doris (filename,date_depot,date_extraction,centrale,id_doc_type) VALUES (:filename,:date_depot,:date_extraction,:centrale,:id_doc_type)');
	$result=$req->execute(array(
		':filename'		=>$filename,
		':date_depot'	=> date('Y-m-d'),
		':date_extraction'=>$dorisDate,
		':centrale'		=>$centrale,
		':id_doc_type'	=>19
	));
	return $result;
}

function dorisAlreadyInDb($pdoBt, $filename)
{
	$req=$pdoBt->prepare("SELECT * FROM doris  WHERE filename= :filename ");
	$req->execute(array(
		'filename'	=>$filename
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}
// function dorisAlreadyInDb($pdoBt)
// {
// 	$req=$pdoBt->prepare("SELECT max(date_extraction) as last FROM doris ");
// 	$req->execute();
// 	return $req->fetch(PDO::FETCH_ASSOC);
// }


$dorisDir="D:\btlec\doris";
$dorisFileList = scandir($dorisDir);
$newdoris=0;
// parcours la liste des fichier du répoertoire doris
foreach ($dorisFileList as $filename)
{

	// récup la date de dépot du fichier
	$fileDate=date ('Y-m-d H:i:s', filemtime($dorisDir.'\\'.$filename));
	$lastMonth= new DateTime(date('Y-m').'-01');
	$lastMonth=$lastMonth->modify('-1 month');
	$objfileDate=new DateTime($fileDate);
	// on trie les noms de fichiers en ne prenant que ceux du mois en cours et du mois dernier => inutile, il repousse tout tout les jours

		// on vérifie si ces fichiers sont déjà dans la db si ils n'y sont pas on les ajoute
	$nePasTraiter=dorisAlreadyInDb($pdoBt, $filename);


	if(empty($nePasTraiter))
	{
		// echo 'à traiter '. $fileDate . ' '. $filename .'<br>';

		// découpe du nom de fichier
		$explodedFilename=explode('-',$filename);
		// echo count($explodedFilename) .' :  ' .$filename.'<br>';
		// les doris exploitable ont seulement 2 tirets  donc fichier découpé en 3
		if(count($explodedFilename)==3 && $explodedFilename[1]!=" ")
		{
		// on récupère les infos du fichiers en passant le filename découpé par - à la fonction
			list($dorisDate,$centrale)=fileInfos($explodedFilename);
			// echo $filename .'<br>';

			if(!empty($dorisDate) && !empty($centrale))
			{
			$dorisDate=$dorisDate->format('Y-m-d');
			$result=addDorisToDb($pdoBt,$filename,$dorisDate,$centrale);
			$newdoris++;

			}

		}

	}


}

if($newdoris>0)
{
		$fileList[]="les analyses Doris";
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

	mailUpdateDoc($mailingList,$subject,$fileList, $today);
}
