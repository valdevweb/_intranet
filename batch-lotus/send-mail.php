<?php


function mail_attachment($filename, $path, $mailto, $fromMail, $fromName, $replyto, $subject, $message) {
    $file = $path.$filename;
    $file_size = filesize($file);
    $handle = fopen($file, "r");
    $content = fread($handle, $file_size);
    fclose($handle);
    $content = chunk_split(base64_encode($content));
    $uid = md5(uniqid(time()));
    $name = basename($file);
    $header = "From: ".$fromName." <".$fromMail.">\r\n";
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


mail_attachment("test.txt", "D:\www\_intranet\_btlecest\batch-lotus\\", "vmontusclat@gmail.com","vmontusclat@gmail.com", "val", "vmontusclat@gmail.com", "test fn pourrie", "ooucou", "vmontusclat@gmail.com");