<?php


if(isset($_POST['validate'])){
	if(!empty($_POST['cmt']))
	{

		$action=addAction($pdoLitige, 3);
		if($action==1){
			$result=updateCommission($pdoLitige,$_POST['iddossier'],1);
		}
		else{
			$errors[]="impossible d'ajouter le commentaire";
		}
		if($result==1)
		{
			header('Location:bt-litige-encours.php#'.$_POST['iddossier']);

		}
		else{
			$errors[]="impossible de mettre le statut à jour";
		}
	}
	else{
		$errors[]="Veuillez saisir un commentaire";
	}
}

if(isset($_POST['chg_pending'])){

	foreach ($_POST as $key => $value) {
		if($key !='chg_pending'){
		// recup le nom du champ et le découpe :
		// pending-box-id-etat
			$idforcom=explode('-',$key);
			if($idforcom[2]==1){
				$etat=0;
			}else{
				$etat=1;
			}
			$done=updateCommission($pdoLitige,$idforcom[1],$etat);
			if($done==1){
				unset($_POST);
				header("Location: ".$_SERVER['PHP_SELF'],true,303);
			}
		}
	}


}