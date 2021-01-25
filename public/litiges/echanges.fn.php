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

function createFileLink($filelist)
{
	$rValue='';
	$filelist=explode(';',$filelist);

	for ($i=0; $i < count($filelist); $i++)
	{
		if($filelist[$i] !="")
		{
			$rValue.='<a href="'.URL_UPLOAD.'/litiges/'.$filelist[$i].'"><span class="pr-3"><i class="fas fa-link"></i></span></a>';

		}
	}
	return $rValue;
}

