<?php


class SalonDao{

	// pdoOcc
	private $pdo;
	private $yearSalon="2021";

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}


	function getFunction(){
		$req=$this->pdo->prepare("SELECT * FROM salon_fonction ORDER BY fonction");
		$req->execute();
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function addParticipant($mobile){
		$req=$this->pdo->prepare("INSERT INTO salon_{$this->yearSalon} (date_saisie, id_web_user, galec, genre, nom, prenom, id_fonction, email, mobile, mardi, mercredi, repas_mardi, repas_mercredi)
			VALUES (:date_saisie, :id_web_user, :galec, :genre, :nom, :prenom, :id_fonction, :email, :mobile, :mardi, :mercredi, :repas_mardi, :repas_mercredi)");
		$req->execute(array(
			':date_saisie'			=>date('Y-m-d H:i:s'),
			':id_web_user'			=>$_SESSION['id_web_user'],
			':galec'			=>$_SESSION['id_galec'],
			':genre'			=>$_POST['genre'],
			':nom'			=>$_POST['nom'],
			':prenom'			=>$_POST['prenom'],
			':id_fonction'			=>$_POST['fonction'],
			':email'			=>$_POST['email'],
			':mobile'			=>$mobile,
			':mardi'			=>$_POST['mardi'],
			':mercredi'			=>$_POST['mercredi'],
			':repas_mardi'			=>$_POST['repas-mardi'],
			':repas_mercredi'			=>$_POST['repas-mercredi'],
		));
		return $req->rowCount();
	}


	public function getParticipant(){
	$req=$this->pdo->prepare("SELECT * FROM salon_{$this->yearSalon} LEFT JOIN qrcode ON salon_{$this->yearSalon}.id=qrcode.id WHERE galec= :galec");
	$req->execute(array(
		':galec'	=>$_SESSION['id_galec']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

public function getOneBadge($idSalon){
	$req=$this->pdo->prepare("SELECT * FROM salon_{$this->yearSalon} LEFT JOIN qrcode ON salon_{$this->yearSalon}.id=qrcode.id LEFT JOIN salon_fonction ON id_fonction= salon_fonction.id WHERE salon_{$this->yearSalon}.id= :id");
	$req->execute(array(
		':id'	=>$idSalon
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

}