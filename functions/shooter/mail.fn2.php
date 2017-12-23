<?php

//-------------------------------------
//				MAIL TO
//-------------------------------------

// le slug (query string correspond au nom de la mailing list)
// function sentTo($pdoBt, $slug)
// {
// 	$req=$pdoBt->prepare('SELECT ldnames.id FROM ldnames LEFT JOIN lk_mailing ON ldnames.id=lk_maling.id_mailing WHERE name= :name');
// 	$req->execute(array(
// 		':name' =>$slug
// 	));
// 	return $req->fetchAll(PDO::FETCH_ASSOC);

	// if($idld=$req->fetch(PDO::FETCH_ASSOC))
	// 	{
	// 		$req=$pdoBt->prepare('SELECT * FROM lk_mailing LEFT JOIN mail ON lk_mailing.id=lk_mailinglist.id_mail WHERE mail.id= :idld');
	// 		$req->execute(array(
	// 			':idld'	=>$idld['id']
	// 		));
	// 	return $req->fetchAll(PDO::FETCH_ASSOC);
	// 	}

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

// $req=$db->prepare('SELECT * FROM services WHERE slug = :gt');
// 	$req->execute(array(



//-------------------------------------
//				FROM
//-------------------------------------

// recup adresse mail du session id

function sentBy($pdoBt,$idSession)
{

}

//-------------------------------------
//				file
//-------------------------------------

// si fichier récup nom fichier et ajoute chemin
//			prepareMail($_POST['objet'],$_POST['msg'],$md5['success']);

function buildheader($from){

	// $boundary = md5(uniqid(time()));
	// $headers = "From: $from \n";
	// $headers .= "Reply-to: $from \n";
	// $headers .= "X-Priority: 1 \n";
	// $headers .= "MIME-Version: 1.0 \n";
	// $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\" \n";
	// $headers .= " \n";
	//
		$boundary_pieces	=	md5('pieces'.uniqid(rand()));
		$headers	=	"From: moi <valerie.montusclat@btlec.fr>\r\n";
		$headers	.=	"MIME-Version: 1.0\r\n";
		$headers	.=	"Content-Type: multipart/mixed; boundary=$boundary_pieces\r\n";

	return $headers;
}


function pj($file,$type){
	$boundary = md5(uniqid(time()));
	$data = chunk_split( base64_encode(file_get_contents($file)) );
	$message  = "--$boundary \n";
	$message .= "Content-Type: text/html; charset=\"iso-8859-1\" \n";
	$message .= "Content-Transfer-Encoding:8bit \n";
	$message .= "\n";
	$message .= "Bonjour,<br/>";
	$message .=  "\r\n";
	$message .= "\n";
	$message .= "--$boundary \n";
	$message .= "Content-Type: $type; name=\"$file\" \n";
	$message .= "Content-Transfer-Encoding: base64 \n";
	$message .= "Content-Disposition: attachment; filename=\"$file\" \n";
	$message .= "\n";
	$message .= $data."\n";
	$message .= "\n";
	$message .= "--".$boundary."--";






return $message;
}




/* Destinataire (votre adresse e-mail) */
$to = 'valerie.montusclat@btlec.fr';
$expediteur='adresse expéditeur';

//piece jointe
$nom_fichier = "image.png";
$typepiecejointe = filetype($nom_fichier);
$data = chunk_split( base64_encode(file_get_contents($nom_fichier)) );

// Création du séparateur
$boundary = md5(uniqid(time()));

$headers = "From: $expediteur \n";
$headers .= "Reply-to: $adresse_retour \n";
$headers .= "X-Priority: 1 \n";
$headers .= "MIME-Version: 1.0 \n";
$headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\" \n";
$headers .= " \n";






/* Construction du message */

$message  = "--$boundary \n";
$message .= "Content-Type: text/html; charset=\"iso-8859-1\" \n";
$message .= "Content-Transfer-Encoding:8bit \n";
$message .= "\n";
$message .= "Bonjour,<br/>";
$message .= $msg."\r\n";
$message .= "\n";
$message .= "--$boundary \n";
$message .= "Content-Type: $typepiecejointe; name=\"$nom_fichier\" \n";
$message .= "Content-Transfer-Encoding: base64 \n";
$message .= "Content-Disposition: attachment; filename=\"$nom_fichier\" \n";
$message .= "\n";
$message .= $data."\n";
$message .= "\n";
$message .= "--".$boundary."--";


/* Envoi de l'e-mail */
//mail($to, $objet, $message, $headers);
//http://a-pellegrini.developpez.com/tutoriels/php/mail/#L4.1
 ?>



