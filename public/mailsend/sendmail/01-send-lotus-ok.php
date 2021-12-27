<?php
if(VERSION=="_"){
	$to = 'vmontusclat@gmail.com;valerie.montusclat@btlecest.leclerc';
	$cc = 'vmontusclat@gmail.com;valerie.montusclat@btlecest.leclerc';

}else{
	$to = $_POST['to-email'];
	$cc=$_POST['cc-email'];
}
$headers = "From: ". $_POST['from_name']. " <".$from.">";
// $headers = "From: ". $_POST['from_name']. " <".$_POST['from_email'].">\r\n";
// $headers .= "Cc: ".$cc."\r\n";
$subject =$_POST['objet'];
$message = $_POST['mail'];


$semi_rand = md5(time());
$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

    // headers for attachment
$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";

    // multipart boundary
$message = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type: text/plain; charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n";
$message .= "--{$mime_boundary}\n";

if(isset($_POST['file_file'])){
	for ($i=0; $i < count($_POST['file_file']) ; $i++) {
		$file=DIR_UPLOAD."email\\".$_POST['file_file'][$i];


		$handle  = fopen($file,"r");
		$data = fread($handle ,filesize($file));
		fclose($handle);
		$data = chunk_split(base64_encode($data));

		$message .= "Content-Type: application/octet-stream; name=\"".$_POST['file_file'][$i]."\"\r\n";


			// $message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$files[$x]\"\n" ;
		$message .="Content-Disposition: attachment;\n" . " filename=\"".$_POST['file_file'][$i]."\"\n" ;
		$message .="Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
		$message .= "--{$mime_boundary}\n";
	}
}

if(mail($to, $subject, $message, $headers)){
	$successQ='?success=send';
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);


}else{
	$errors[]="une erreur s'est produite, le mail n'a pas pu être envoyé";
}
