<?php

function getDialog($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT id_dossier,DATE_FORMAT(date_saisie, '%d-%m-%Y') as dateFr, DATE_FORMAT(date_saisie, '%H:%i') as heure,msg,id_web_user,filename,mag FROM dial WHERE id_dossier= :id AND mag!=3 ORDER BY date_saisie DESC");
	$req->execute(array(
		':id'		=>$_GET['id']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);

}
$dials=getDialog($pdoLitige);



function getBtName($pdoBt, $idwebuser)
{
	$req=$pdoBt->prepare("SELECT CONCAT (prenom, ' ', nom) as name FROM btlec WHERE id_webuser= :id_web_user");
	$req->execute(array(
		':id_web_user'	=>$idwebuser
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}


function getMagName($pdoUser, $idwebuser)
{
	$req=$pdoUser->prepare("SELECT btlec.sca3.mag, btlec.sca3.btlec FROM btlec.sca3 LEFT JOIN web_users.users ON btlec.sca3.galec= web_users.users.galec WHERE web_users.users.id= :id_web_user ");
	$req->execute(array(
		':id_web_user'	=>$idwebuser
	));
	return $req->fetch(PDO::FETCH_ASSOC);

}

function createFileLink($filelist)
{
	$rValue='';
	$filelist=explode(';',$filelist);

	for ($i=0; $i < count($filelist); $i++)
	{
		if($filelist[$i] !="")
		{
			$rValue.='<a href="'.UPLOAD_DIR.'/litiges/'.$filelist[$i].'"><span class="pr-3"><i class="fas fa-link"></i></span></a>';

		}
	}
	return $rValue;
}




