<?php
include('../../config/autoload.php');


if(!empty($_POST))
{
	foreach ($_POST['table'] as  $value)
	{
		$split_data=explode('_',$value);
		$fileId=$split_data[0];
		$newOrder=$split_data[1];
		$req=$pdoBt->prepare("UPDATE pres_files SET ordre = :newOrder WHERE id= :fileId");
		$done=$req->execute(array(
			':newOrder'		=>$newOrder,
			'fileId'		=>$fileId
		));
	}
	if($done)
	{
		echo "l'ordre des documents a bien été modifié";
	}
	else
	{
		echo "erreur : la mise a jour a échouée";
	}
}



 ?>