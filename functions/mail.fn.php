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