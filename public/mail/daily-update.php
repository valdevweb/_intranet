<?php
//------------------------------------------------------
//			INFOS
//------------------------------------------------------
/*
Page lancée par tâche planifiée à 18h du lundi au vendredi
pas d'autoload car :
- include impossible
- pas de démarrage de session

 */
//------------------------------------------------------
//			DETECTION ENVIRONNEMENT DEV OU PROD
//------------------------------------------------------
$path=dirname(__FILE__);
if (preg_match('/_btlecest/', $path)){
	$database='_btlec';
	$prefix='_';
	$mailingList="valerie.montusclat@btlec.fr";
}
else{
	$database='btlec';
	$prefix='';
	$mailingList="btlecest.portailweb.gazettes@btlec.fr";
	// $mailingList="valerie.montusclat@btlec.fr";

}

require_once  'D:\www\\'.$prefix.'intranet\\'.$prefix.'btlecest\vendor\autoload.php';


function dbConnect($database){
	$host='localhost';
	$username='sql';
	$pwd='User19092017+';
	try {
		$pdoBt=new PDO("mysql:host=$host;dbname=$database", $username, $pwd);
		return $pdoBt;
	}
	catch(Exception $e){
		die('Erreur : '.$e->getMessage());
		return false;
	}
}

$pdoBt=dbConnect($database);


//------------------------------------------------------
//			INIT
//------------------------------------------------------
$jours=array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
$months=array("","janvier", "février","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","décembre");
$today= $jours[date('w')] .' '. date('d'). ' ' . $months[date('n')] . ' '. date('Y') ;
//liste des fichiers mis à jour depuis hier 18h
$fileList=array();
$deadLine=new DateTime('yesterday 18:00:00');


//------------------------------------------------------
//			OPPORTUNITES
//------------------------------------------------------
$opp="D:\www\intranet\opportunites\index.html";
if (file_exists($opp)){
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
if($files>=1){
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

function fileInfos($explodedFilename){
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

function dorisAlreadyInDb($pdoBt, $filename){
	$req=$pdoBt->prepare("SELECT * FROM doris  WHERE filename= :filename ");
	$req->execute(array(
		'filename'	=>$filename
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}



$dorisDir="D:\btlec\doris";
$dorisFileList = scandir($dorisDir);
$newdoris=0;
// parcours la liste des fichier du répoertoire doris
foreach ($dorisFileList as $filename){

	// récup la date de dépot du fichier
	$fileDate=date ('Y-m-d H:i:s', filemtime($dorisDir.'\\'.$filename));
	$lastMonth= new DateTime(date('Y-m').'-01');
	$lastMonth=$lastMonth->modify('-1 month');
	$objfileDate=new DateTime($fileDate);
	// on trie les noms de fichiers en ne prenant que ceux du mois en cours et du mois dernier => inutile, il repousse tout tout les jours

		// on vérifie si ces fichiers sont déjà dans la db si ils n'y sont pas on les ajoute
	$nePasTraiter=dorisAlreadyInDb($pdoBt, $filename);


	if(empty($nePasTraiter)){
		// découpe du nom de fichier
		$explodedFilename=explode('-',$filename);
		// echo count($explodedFilename) .' :  ' .$filename.'<br>';
		// les doris exploitable ont seulement 2 tirets  donc fichier découpé en 3
		if(count($explodedFilename)==3 && $explodedFilename[1]!=" "){
		// on récupère les infos du fichiers en passant le filename découpé par - à la fonction
			list($dorisDate,$centrale)=fileInfos($explodedFilename);
			// echo $filename .'<br>';

			if(!empty($dorisDate) && !empty($centrale)){
				$dorisDate=$dorisDate->format('Y-m-d');
				$result=addDorisToDb($pdoBt,$filename,$dorisDate,$centrale);
				$newdoris++;
			}
		}
	}
}

if($newdoris>0){
	$fileList[]="les analyses Doris";
}



//------------------------------------------------------
//			si list fichiers non vide => envoi mail
//------------------------------------------------------

if(count($fileList)!=0){

	$htmlfileList="<ul>";
	for ($i=0;$i<count($fileList);$i++) {
		$htmlfileList.="<li>".$fileList[$i].'</li>';
	}
	$htmlfileList.="</ul>";
		ob_start();
		include('mail-daily.html');
		$htmlMail=ob_get_contents();
		ob_end_clean();
	// $htmlMail = file_get_contents('mail-daily.html');
	$htmlMail=str_replace('{TODAY}',$today,$htmlMail);
	$htmlMail=str_replace('{FILELIST}',$htmlfileList,$htmlMail);
	$subject='Portail BTLec Est - vos infos du jour';

// ---------------------------------------
// initialisation de swift
	$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
	$mailer = new Swift_Mailer($transport);
	$message = (new Swift_Message($subject))
	->setBody($htmlMail, 'text/html')
	->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec'))
	->setTo($mailingList)
	->addBcc('valerie.montusclat@btlec.fr');

	if (!$mailer->send($message, $failures)){

	}else{
		$success[]="mail envoyé avec succés";


	}

}
