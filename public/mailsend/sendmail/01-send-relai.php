<?php
echo "envoi via informadis<br>";

function getEmails($pdoMag,$string){
	$listEmail=[];
	if (str_contains($string, '@')){
			// on a une adresse normale
		$listEmail[0]=['email'=>$string];
		return $listEmail;

	}else{


		$patternCodeBt="/^\d+/";
		// adresse = liste de diffu avec code bt
		if(preg_match($patternCodeBt,$string)){
			$req=$pdoMag->prepare("SELECT emails.email, concat(btlec,suffixe) as namebt FROM listdiffu LEFT JOIN listdiffu_email ON listdiffu.id=listdiffu_email.id_listdiffu LEFT JOIN emails on listdiffu_email.id_email=emails.id WHERE concat(btlec,suffixe)=:name ");
			$req->execute([
				':name'=>$string
			]);
			$listEmail=$req->fetchAll();

			if (!empty($listEmail)) {
				return $listEmail;
			}else{
				return "";
			}
		}else{
			$req=$pdoMag->prepare("SELECT emails.email, concat(name,suffixe) as name FROM listdiffu LEFT JOIN listdiffu_email ON listdiffu.id=listdiffu_email.id_listdiffu LEFT JOIN emails on listdiffu_email.id_email=emails.id WHERE concat(name,suffixe)=:name ");
			$req->execute([
				':name'=>$string
			]);
			$listEmail=$req->fetchAll();

			if (!empty($listEmail)) {
				return $listEmail;
			}else{
				return "";
			}
		}

	}
}
$toStr = $_POST['to-email'];
$dest=[];

$toArray=explode(";",$toStr);
$toArray=array_filter($toArray);
$toArray=array_values($toArray);


for ($i=0; $i < count($toArray) ; $i++) {
	$toSql=getEmails($pdoMag, $toArray[$i]);

	$toArray[$i];
	if (empty($toSql)) {
		$errors[]="Impossible d'envoyer le mail la liste de diffu ".$toArray[$i]. " est vide";
	}else{
		foreach ($toSql as $key => $toS) {
			$dest[]=$toS['email'];
		}
	}
}


$ccStr=$_POST['cc-email'];
$cc=[];
if($ccStr!=""){
	$ccArray=explode(";",$ccStr);
	$ccArray=array_filter($ccArray);
	$ccArray=array_values($ccArray);
	for ($i=0; $i < count($ccArray) ; $i++) {
		$ccSql=getEmails($pdoMag, $ccArray[$i]);
		if (empty($ccSql)) {
			$errors[]="Impossible d'envoyer le mail la liste de diffu ".$ccArray[$i]; " est vide";
		}else{
			foreach ($ccSql as $key => $ccS) {
				$cc[]=$ccS['email'];
			}
		}
	}

}


$prodDest="";
$prodCc="";
if(VERSION=="_"){
	$prodDest='dest : '.implode(";",$dest);
	$prodDest.='<br>';
	$prodCc='cc '.implode(';',$cc);
	$dest = ['vmontusclat@gmail.com','valerie.montusclat@btlecest.leclerc'];
	$cc = ['vmontusclat@gmail.com','valerie.montusclat@btlecest.leclerc'];
}


$transport = (new Swift_SmtpTransport(SMTP_ADDRESS, 25));
$mailer = new Swift_Mailer($transport);

$htmlMail=$_POST['mail'].$prodDest.$prodCc;
$subject='Portail BTlec '.$_POST['objet'];


$message = (new Swift_Message($subject))
->setBody($htmlMail, 'text/html')
->setFrom(array($_POST['from_email'] => $_POST['from_name']))
->setTo($dest)
->setCc($cc);

if(isset($_POST['file_file'])){
	for ($i=0; $i < count($_POST['file_file']) ; $i++) {
		$file=DIR_UPLOAD."email\\".$_POST['file_file'][$i];
		$message->attach(Swift_Attachment::fromPath($file));
	}
}

if (!$mailer->send($message, $failures)){
	print_r($failures);
}else{
	$success[]="mail envoyé avec succés";
	$successQ='?success=send';
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
}

