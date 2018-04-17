<?php
/**
 * fonction générique d'envoi de mail
 * @param  $mailingList liste de diffu ou mail
 * @param  $subject     objet du mail
 * @param  $tplLocation emplacement du fichier template
 * @param  $contentOne  contenu dynamique 1 du tplt
 * @param  $contentTwo  contenu dynamique 2 du tplt
 * @param  $link        contenu dynamique = lien vers page du site
 * @return true or false
 */
function sendMail($mailingList,$subject,$tplLocation,$contentOne,$contentTwo,$link)
{
	$tpl = file_get_contents($tplLocation);
	$tpl=str_replace('{CONTENT1}',$contentOne,$tpl);
	$tpl=str_replace('{CONTENT2}',$contentTwo,$tpl);
	$tpl=str_replace('{LINK}',$link,$tpl);


	$htmlContent=$tpl;
// Set content-type header for sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// Additional headers
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




//mail avec piece jointe (utilisé par le salon)
function mail_attachment($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message) {
    $file = $path.$filename;
    $file_size = filesize($file);
    $handle = fopen($file, "r");
    $content = fread($handle, $file_size);
    fclose($handle);
    $content = chunk_split(base64_encode($content));
    $uid = md5(uniqid(time()));
    $name = basename($file);
    $header = "From: ".$from_name." <".$from_mail.">\r\n";
    $header .= "Reply-To: ".$replyto."\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
    $nmessage = "--".$uid."\r\n";
    $nmessage .= "Content-type:text/plain; charset=iso-8859-1\r\n";
    $nmessage .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    // $nmessage .= $message."\r\n\r\n";
    $nmessage .= str_replace("<br>","\r\n\r\n", $message)."\r\n\r\n";
    $nmessage .= "--".$uid."\r\n";
    $nmessage .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n";
    $nmessage .= "Content-Transfer-Encoding: base64\r\n";
    $nmessage .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
    $nmessage .= $content."\r\n\r\n";
    $nmessage .= "--".$uid."--";
    error_reporting(E_ALL);
    if (mail($mailto, $subject, $nmessage, $header)) {
        // $result= "Mail envoyé avec succés à " . $mailto ."<br/>";
        $_SESSION['notification']['success']="Mail envoyé avec succés à " . $mailto ."<br/>";
    } else {
        // $result= "Erreur lors de l'envoi du mail à  " .$mailto ."<br/>";
        $_SESSION['notification']['error']="Erreur lors de l'envoi du mail à  " .$mailto ."<br/>";

    }
    // return $result;

}

function sendMailContact($to,$subject,$formMail, $formMsg)
{
// Set content-type header for sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// Additional headers
    $headers .= 'From: '. $formMail . "\r\n";
    $headers .= 'Cc: ' . "\r\n";
    $headers .= 'Bcc:' . "\r\n";
    $formMsg=nl2br($formMsg);
    if(mail($to,$subject,$formMsg,$headers))
    {
        return true;
    }
    else
    {
        return false;
    }

}