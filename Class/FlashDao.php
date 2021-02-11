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

	public function insertFlash(){
		$req=$this->pdo->prepare("INSERT INTO flash_info (title, content, portail_bt, portail_sav, portail_fournisseur, date_start, date_end, id_web_user, date_insert) VALUES (:title, :content, :portail_bt, :portail_sav, :portail_fournisseur, :date_start, :date_end, :id_web_user, :date_insert)");
		$req->execute([
			':title'	=>$_POST['title'],
			':content'	=>$_POST['content'],
			':portail_bt'	=>(isset($_POST['portail_bt']) && ($_POST['portail_bt']==1))?1:0,
			':portail_sav'	=>(isset($_POST['portail_sav']) && ($_POST['portail_sav']==1))?1:0,
			':portail_fournisseur'	=>(isset($_POST['portail_fournisseur']) && ($_POST['portail_fournisseur']==1))?1:0,
			':date_start'	=>$_POST['date_start'],
			':date_end'	=>$_POST['date_end'],
			':id_web_user'	=>$_SESSION['id_web_user'],
			':date_insert'	=>date('Y-m-d H:i:s')
		]);
		return $this->pdo->lastInsertId();
	}
	public function updateFlash($id){
		$req=$this->pdo->prepare("UPDATE flash_info SET title= :title, content= :content, portail_bt= :portail_bt, portail_sav= :portail_sav, portail_fournisseur= :portail_fournisseur, date_start= :date_start, date_end= :date_end, date_insert = :date_insert WHERE id= :id");
		$req->execute([
			':title'	=>$_POST['title'],
			':content'	=>$_POST['content'],
			':portail_bt'	=>(isset($_POST['portail_bt']) && ($_POST['portail_bt']==1))?1:0,
			':portail_sav'	=>(isset($_POST['portail_sav']) && ($_POST['portail_sav']==1))?1:0,
			':portail_fournisseur'	=>(isset($_POST['portail_fournisseur']) && ($_POST['portail_fournisseur']==1))?1:0,
			':date_start'	=>$_POST['date_start'],
			':date_end'	=>$_POST['date_end'],
			':id'	=>$id,
			':date_insert'	=>date('Y-m-d H:i:s')

		]);
		return $this->pdo->lastInsertId();
	}


	public function getFlash($id){
		$req=$this->pdo->prepare("SELECT * FROM flash_info WHERE id= :id");
		$req->execute([
			':id'		=>$id
		]);
		return $req->fetch(PDO::FETCH_ASSOC);
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
}





