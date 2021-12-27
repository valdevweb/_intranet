<?php
echo "envoi via lotus<br>";

$semi_rand = md5(time());
$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

$newTo=[];
$newCc=[];
$to = $_POST['to-email'];
$toArray=explode(";",$to);
$toArray=array_filter($toArray);
$toArray=array_values($toArray);

for ($i=0; $i < count($toArray); $i++) {
	if(str_contains($toArray[$i], '@')){
		$newTo[]=$toArray[$i];
	}else{
		$newTo[]=$toArray[$i]."@btlec.fr";
	}
}
$to=implode(";", $newTo);
	// echo "to prod".$to;
$cc=$_POST['cc-email'];
$ccArray=explode(";",$cc);
$ccArray=array_filter($ccArray);
$ccArray=array_values($ccArray);
for ($i=0; $i < count($ccArray); $i++) {
	if(str_contains($ccArray[$i], '@')){
		$newCc[]=$ccArray[$i];
	}else{
		$newCc[]=$ccArray[$i]."@btlec.fr";
	}
}
$cc=implode(";", $newCc);

$prodCc="";
$prodTo="";


if(VERSION=="_"){
	$prodCc="<br>CC :".$cc;
	$prodTo="to :".$to;

	$to = 'vmontusclat@gmail.com';
	$cc="valerie.montusclat@btlecest.leclerc";
}
$headers = "From: ". $_POST['from_name']. " <".$from.">".PHP_EOL;
$headers .= "Cc: ".$cc.PHP_EOL;



    // headers for attachment
$headers .= "MIME-Version: 1.0".PHP_EOL;
$headers.= "Content-Type: multipart/mixed;".PHP_EOL;
$headers.= " boundary=\"{$mime_boundary}\"";



$message = $_POST['mail'];
$message.= $prodTo;
$message.=$prodCc;

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
		$message .="Content-Disposition: attachment;\n" . " filename=\"".$_POST['file_file'][$i]."\"\n" ;
		$message .="Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
		$message .= "--{$mime_boundary}\n";
	}
}

$subject =$_POST['objet'];

if(mail($to, $subject, $message, $headers)){

	$successQ='?success=send';
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);


}else{
	$errors[]="une erreur s'est produite, le mail n'a pas pu être envoyé";
}
