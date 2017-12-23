<?php
//----------------------------------------------------------------
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){

	header('Location:'. ROOT_PATH.'/index.php');
}
//----------------------------------------------------------------
require_once '../../functions/form.fn.php';
require_once '../../functions/mail.fn.php';

//----------------------------------------------------------------
$id_mag=$_SESSION['id'];
//----------------------------------------------------------------


//----------------------------------------------------------------
//			affichage : infos du services + message non clos
//----------------------------------------------------------------
$gt=$_GET['gt'];
$gtInfos=initForm($pdoBt,$gt);
foreach($gtInfos as $data)
{
	$full_name= $data['full_name'];
 	$descr= $data['description'];
 	//numero du service
 	$idGt=$data['id'];
}
$names=getNames($pdoBt, $idGt);
$nbName=sizeof($names);
//messages
$allMagMsg=showAllMsg($pdoBt,$id_mag);

//----------------------------------------------------------------
//			traitement envoi mail
//----------------------------------------------------------------

//recup ld en fonction du slug
// $ld=sentTo($pdoBt, $gt);
// //formatage de la  list de diffu
// foreach ($ld as $email) {
// 	$listdiffu.= $email['mail'].', ';
// }

// echo $listdiffu;
 $from=buildheader('valerie.montusclat@btlec.fr');
 	echo "contenu du header :<pre>";
 	var_dump($from);
 	echo '</pre>';




//----------------------------------------------------------------
//			traitement du message : ajout à db et upload si fichier
//----------------------------------------------------------------
if(!empty($_POST))
{
	if((empty($_POST['objet']) OR empty($_POST['msg'])))
	{
		echo "merci de remplir tous les champs";
	}
	else
	{
		$obj=$_POST['objet'];
		$msg=$_POST['msg'];
		$to='valerie.montusclat@btlec.fr';
		$objet="PORTAIL : demande magasin recue";
		$from="portail@btlec.fr";
		$headers=buildheader($from);

		if (empty($_FILES['file']['name']))
		{
			//echo "pas de fichier joint";
			addMsg($pdoBt,$_POST['objet'],$_POST['msg'],$id_mag,$idGt, $file);
			$mailTemplate=file_get_contents('mail_template.html');
			$mailTemplate=str_replace('{{obj}}',$obj,$mailTemplate);
			$mailTemplate=str_replace('{{msg}}',$msg,$mailTemplate);
			echo "message";
			var_dump($mailTemplate);
			//mail($to, $objet, $message, $headers);


		}
		else
		{
			$file=$_FILES['file']['name'];
			echo "nom fichier". $file;
			$type=$_FILES['file']['type'];
			echo "type fichier " .$type;

			$upload=$_FILES['file'];
			$uploadDir= '..\..\..\upload\mag\\';
			$md5=checkUpload($upload, $uploadDir, $pdoBt);
	 	    addMsg($pdoBt,$_POST['objet'],$_POST['msg'],$id_mag,$idGt, $md5['success']);

	 	//     $mailTemplate=file_get_contents('mail_template.html');
			// $mailTemplate=str_replace('{{obj}}',$obj,$mailTemplate);
			// $mailTemplate=str_replace('{{msg}}',$msg,$mailTemplate);
			//
			$mailTemplate="TEST";
			// echo "message avec pj";
			// var_dump($mailTemplate);
	 	   // $pj=pj($file,$type);
	 	    // echo 'pj detail';
	 	    // var_dump($pj);
	 	   // $mailTemplate.=$pj;
			var_dump($mailTemplate);

			mail($to, $objet, $pj, $headers);

		}

	}
}
else
{
	//echo "la demande n'a pas pu être envoyée";
}














//header et nav bar
include ('../view/_head.php');
include ('../view/_navbar.php');
// echo "session " . $_SESSION['id'];
//contenu
include('contact.ct.php');


// footer avec les scripts et fin de html
include('../view/_footer.php');

