<?php


//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
function getThisOuverture($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT ouv.id, DATE_FORMAT(date_saisie, '%d-%m-%Y') as datesaisie, msg, pj, mag, btlec, ouv.galec, ouv.etat FROM ouv LEFT JOIN btlec.sca3 ON ouv.galec=btlec.sca3.galec WHERE ouv.id= :id");
	$req->execute(array(
		':id'		=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

function getRep($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT id, id_web_user, DATE_FORMAT(date_saisie, '%d-%m-%Y') as datesaisie, msg,pj, mag FROM ouv_rep WHERE id_ouv= :id ORDER BY date_saisie");
	$req->execute(array(
		':id'		=>$_GET['id']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getBtName($pdoBt, $idwu)
{
	$req=$pdoBt->prepare("SELECT CONCAT (prenom, ' ', nom) as fullname FROM btlec WHERE id_webuser= :id_webuser");
	$req->execute(array(
		':id_webuser'	=>$idwu
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
			$rValue.='<a href="'.UPLOAD_DIR.'/litiges/'.$filelist[$i].'" class="link-grey"><span class="pr-3"><i class="fas fa-link"></i></span></a>';

		}
	}
	return $rValue;
}


function getInfoMag($pdoBt, $galec)
{
	$req=$pdoBt->prepare("SELECT btlec FROM sca3 WHERE galec = :galec");
	$req->execute(array(
		':galec'		=>$galec,
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}



