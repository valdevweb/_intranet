<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}


include 'config/config.inc.php';
include 'config/db-connect.php';
include 'vendor/autoload.php';



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

function getTodayOpp($pdoBt){
	$req=$pdoBt->prepare("SELECT * FROM opp WHERE date_start= :date_start");
	$req->execute([
		':date_start' =>date('Y-m-d')
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}


if (VERSION=="_"){

	$mailingList="valerie.montusclat@btlec.fr";
}
else{

	$mailingList="btlecest.portailweb.gazettes@btlec.fr";
	// $mailingList="valerie.montusclat@btlec.fr";

}



//------------------------------------------------------
//			INIT
//------------------------------------------------------
$jours=array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
$months=array("","janvier", "février","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","décembre");
$today= $jours[date('w')] .' '. date('d'). ' ' . $months[date('n')] . ' '. date('Y') ;
//liste des fichiers mis à jour depuis hier 18h
$fileList=array();
$deadLine=new DateTime('yesterday 18:00:00');



$newOpp=getTodayOpp($pdoBt);


if(!empty($newOpp)){
	if(count($newOpp)==1){
		$strOpp="une nouvelle offre spéciale : <ul>";
	}else{
		$strOpp="de nouvelles offres spéciales : <ul>";

	}
	foreach ($newOpp as $key => $opp) {
		$strOpp.='<li>'.$opp['title'].'</li>';
	}
	$strOpp.='</ul>';
	$fileList[]=$strOpp;

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




$dorisDir="D:\btlec\doris";
$dorisFileList = scandir($dorisDir);
$newdoris=0;
// parcours la liste des fichier du répoertoire doris
foreach ($dorisFileList as $filename){
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
		include('mail-daily.php');
		$htmlMail=ob_get_contents();
		ob_end_clean();
	// $htmlMail = file_get_contents('mail-daily.html');
	$htmlMail=str_replace('{TODAY}',$today,$htmlMail);
	$htmlMail=str_replace('{FILELIST}',$htmlfileList,$htmlMail);
	$htmlMail=str_replace('{SITE_ADDRESS}',SITE_ADDRESS,$htmlMail);
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

	echo $htmlfileList;

	if (!$mailer->send($message, $failures)){

	}else{
		$success[]="mail envoyé avec succés";


	}

}else{

	echo "vide";
}
