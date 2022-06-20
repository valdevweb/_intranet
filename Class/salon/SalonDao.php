<?php


class SalonDao
{

	// pdoOcc
	private $pdo;
	private $yearSalon = "2022";

	public function __construct($pdo)
	{
		$this->setPdo($pdo);
	}
	public function setPdo($pdo)
	{
		$this->pdo = $pdo;
		return $pdo;
	}


	function getFunction()
	{
		$req = $this->pdo->prepare("SELECT * FROM salon_fonction ORDER BY fonction");
		$req->execute();
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function addParticipant()
	{
		$req = $this->pdo->prepare("INSERT INTO salon_{$this->yearSalon} (date_saisie, id_web_user, galec, genre, nom, prenom, id_fonction, email,  mardi, mercredi, repas_mardi, repas_mercredi)
			VALUES (:date_saisie, :id_web_user, :galec, :genre, :nom, :prenom, :id_fonction, :email,  :mardi, :mercredi, :repas_mardi, :repas_mercredi)");
		$req->execute(array(
			':date_saisie'			=> date('Y-m-d H:i:s'),
			':id_web_user'			=> $_SESSION['id_web_user'],
			':galec'			=> $_SESSION['id_galec'],
			':genre'			=> $_POST['genre'],
			':nom'			=> $_POST['nom'],
			':prenom'			=> $_POST['prenom'],
			':id_fonction'			=> $_POST['fonction'],
			':email'			=> $_POST['email'],
			':mardi'			=> $_POST['mardi'],
			':mercredi'			=> $_POST['mercredi'],
			':repas_mardi'			=> $_POST['repas-mardi'],
			':repas_mercredi'			=> $_POST['repas-mercredi'],
		));
		return $this->pdo->lastInsertId();
	}

	public function updateParticipantQrcode($id, $qrcode)
	{
		$req = $this->pdo->prepare("UPDATE salon_{$this->yearSalon} SET qrcode= :qrcode WHERE id=:id");
		$req->execute([
			':id'		=> $id,
			':qrcode'		=> $qrcode
		]);
		return $req->rowCount();
	}

	public function getParticipant()
	{
		$req = $this->pdo->prepare("SELECT salon_{$this->yearSalon}.* FROM salon_{$this->yearSalon}  WHERE galec= :galec");
		$req->execute(array(
			':galec'	=> $_SESSION['id_galec']
		));
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getOneBadge($idSalon)
	{
		$req = $this->pdo->prepare("SELECT * FROM salon_{$this->yearSalon} 
	LEFT JOIN salon_fonction ON id_fonction= salon_fonction.id WHERE salon_{$this->yearSalon}.id= :id");
		$req->execute(array(
			':id'	=> $idSalon
		));
		return $req->fetch(PDO::FETCH_ASSOC);
	}


	public function addlogin($galec, $cp)
	{
		$req = $this->pdo->prepare("SELECT * FROM salon_logins WHERE galec= :galec");
		$req->execute([
			':galec'		=> $galec
		]);
		$data = $req->fetch();
		if (empty($data)) {
			$login = $galec . '@' . $cp;
			// crea pwa
			$specialChars = str_shuffle('!@#$%&*()_-=+;:,.?;');
			$letters = str_shuffle('abcdefghjkmnpqrstuvwxyz');
			$upperLetters = strtoupper($letters);
			$numbers = str_shuffle('0123456789');

			$pwd = substr($upperLetters, 0, 1) . substr($specialChars, 0, 1) . substr($letters, 0, 6);
			$req = $this->pdo->prepare("INSERT INTO salon_logins (galec, login, pwd) VALUES (:galec, :login, :pwd)");
			$req->execute([
				':galec'		=> $galec,
				':login'	=> $login,
				':pwd' => $pwd
			]);
			return $this->pdo->lastInsertId();
		}
	}
}
