<?php

$row=updateCtrl($pdoLitige, 1);
	if($row==1){
		header('Location:bt-action-add.php?id='.$_GET['id'].'&success=ok');
	}
	else{
		$errors[]="impossible de mettre à jour la base de donnée";
	}