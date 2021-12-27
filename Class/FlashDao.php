<?php


class FlashDao{


	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}


	public function insertFlash($title, $content, $portailBt, $portailSav, $portailFou, $dateStart, $dateEnd){
		$req=$this->pdo->prepare("INSERT INTO flash_info (title, content, portail_bt, portail_sav, portail_fournisseur, date_start, date_end, id_web_user, date_insert) VALUES (:title, :content, :portail_bt, :portail_sav, :portail_fournisseur, :date_start, :date_end, :id_web_user, :date_insert)");
		$req->execute([
			':title'	=>$title,
			':content'	=>$content,
			':portail_bt'	=>$portailBt,
			':portail_sav'	=>$portailSav,
			':portail_fournisseur'	=>$portailFou,
			':date_start'	=>$dateStart,
			':date_end'	=>$dateEnd,
			':id_web_user'	=>$_SESSION['id_web_user'],
			':date_insert'	=>date('Y-m-d H:i:s')
		]);
		// return $req->errorInfo();
		return $this->pdo->lastInsertId();
	}
	public function updateFlash($id, $title, $content, $portailBt, $portailSav, $portailFou, $dateStart, $dateEnd){
		$req=$this->pdo->prepare("UPDATE flash_info SET title= :title, content= :content, portail_bt= :portail_bt, portail_sav= :portail_sav, portail_fournisseur= :portail_fournisseur, date_start= :date_start, date_end= :date_end, id_web_user = :id_web_user, date_insert = :date_insert WHERE id= :id");
		$req->execute([
			':title'	=>$title,
			':content'	=>$content,
			':portail_bt'	=>$portailBt,
			':portail_sav'	=>$portailSav,
			':portail_fournisseur'	=>$portailFou,
			':date_start'	=>$dateStart,
			':date_end'	=>$dateEnd,
			':id'	=>$id,
			':id_web_user'	=>$_SESSION['id_web_user'],
			':date_insert'	=>date('Y-m-d H:i:s')

		]);
		// return $req->errorInfo();

		return $id;
	}


	public function getFlash($id){
		$req=$this->pdo->prepare("SELECT * FROM flash_info WHERE id= :id");
		$req->execute([
			':id'		=>$id
		]);
		return $req->fetch(PDO::FETCH_ASSOC);
	}
	public function getLastFlash(){

		$req=$this->pdo->query("SELECT id FROM flash_info ORDER BY id desc LIMIT 1");
		return $req->fetch();
	}

	public function getListFlash($date){
		$req=$this->pdo->prepare("SELECT * FROM flash_info WHERE date_end >= :date_end AND validated= 1 ORDER BY  date_start, date_end");
		$req->execute([

			':date_end'	=>$date,
		]);
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}
	public function getListFlashBySite($date, $site){
		$req=$this->pdo->prepare("SELECT * FROM flash_info WHERE date_start<= :date_start AND date_end >= :date_end AND $site=1 AND validated= 1 ORDER BY  date_start, date_end");
		$req->execute([
			':date_start'	=>$date,
			':date_end'	=>$date,
		]);
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}


	public function updateValidated($validated, $id){
		$req=$this->pdo->prepare("UPDATE flash_info SET validated= :validated, date_insert= :date_insert WHERE id= :id");
		$req->execute([
			':validated'	=>$validated,
			':id'	=>$id,
			':date_insert'	=>date('Y-m-d H:i:s')

		]);
		return $req->rowCount();
	}

	public function deleteFlash($id){
		$req=$this->pdo->prepare("DELETE FROM flash_info WHERE id= :id");
		$req->execute([
			':id'		=>$id
		]);
	}

}





