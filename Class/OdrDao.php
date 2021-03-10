<?php

class OdrDao{

	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function getOdrEncours(){
		$req=$this->pdo->prepare("SELECT *, odr.id as id_odr FROM odr
			WHERE date_end>=:date_end ORDER BY odr.date_insert DESC");
		$req->execute([
			':date_end'	=>date("Y-m-d")

		]);

		return $req->fetchAll();
	}
	public function getOdrEanEncours(){
		$req=$this->pdo->prepare("SELECT odr.id as id_odr, odr_ean.ean, odr_ean.ean_file, odr_ean.ean_filename FROM odr_ean
			LEFT JOIN odr ON odr_ean.id_odr=odr.id
			WHERE date_end>=:date_end ORDER BY odr.date_insert DESC");
		$req->execute([
			':date_end'	=>date("Y-m-d")

		]);

		return $req->fetchAll(PDO::FETCH_GROUP);
	}
	public function getOdrFilesEncours(){
		$req=$this->pdo->prepare("SELECT odr.id as id_odr, odr_files.file, odr_files.filename FROM odr_files
			LEFT JOIN odr ON odr_files.id_odr=odr.id
			WHERE date_end>=:date_end ORDER BY odr.date_insert DESC");
		$req->execute([
			':date_end'	=>date("Y-m-d")

		]);

		return $req->fetchAll(PDO::FETCH_GROUP);
	}




	public function addOdr(){
		$req=$this->pdo->prepare("INSERT INTO odr (date_start, date_end, gt, famille, marque, by_insert, date_insert) VALUES (:date_start, :date_end, :gt, :famille, :marque, :by_insert, :date_insert)");
		$req->execute([
			':date_start'	=>$_POST['date_start'],
			':date_end'	=>$_POST['date_end'],
			':gt'	=>$_POST['gt'],
			':famille'	=>$_POST['famille'],
			':marque'	=>$_POST['marque'],
			':by_insert'	=>$_SESSION['id_web_user'],
			':date_insert'	=>date('Y-m-d H:i:s'),

		]);
		return $this->pdo->lastInsertId();
	}

	public function addEanFile($filename, $idOdr){
		$req=$this->pdo->prepare("INSERT INTO odr_ean (id_odr, ean_file) VALUES  (:id_odr, :ean_file)");
		$req->execute([
			':id_odr'		=>$idOdr,
			':ean_file'		=>$filename
		]);
		return ($req->errorInfo());
	}

	public function addEan( $idOdr, $ean){
		$req=$this->pdo->prepare("INSERT INTO odr_ean (id_odr, ean) VALUES  (:id_odr, :ean)");
		$req->execute([
			':id_odr'		=>$idOdr,
			':ean'		=>$ean
		]);
		return ($req->errorInfo());

	}

	public function addOdrFile($idOdr, $filename){
		$req=$this->pdo->prepare("INSERT INTO odr_files (id_odr, file) VALUES (:id_odr, :file)");
		$req->execute([
			':id_odr'		=>$idOdr,
			':file'		=>$filename
		]);
		return ($req->errorInfo());

	}

	public function getOdrById($id){
		$req=$this->pdo->prepare("SELECT * FROM odr WHERE id= :id");
		$req->execute([
			':id'	=>$id
		]);

		return $req->fetch();
	}


	public function getOdrEan($idOdr){
		$req=$this->pdo->prepare("SELECT * FROM odr_ean WHERE id_odr= :id_odr");
		$req->execute([
			':id_odr'	=>$idOdr
		]);

		return $req->fetchAll();

	}
	public function getOdrFiles($idOdr){
		$req=$this->pdo->prepare("SELECT * FROM odr_files WHERE id_odr= :id_odr");
		$req->execute([
			':id_odr'	=>$idOdr
		]);

		return $req->fetchAll();
	}

	public function updateEanFile($id, $eanFile){
		$req=$this->pdo->prepare("UPDATE odr_ean SET ean_file= :ean_file WHERE id= :id");
		$req->execute([
			':ean_file'				=>$eanFile,
			':id'				=>$id
		]);
		return ($req->errorInfo());

	}

	public function updateEan($keyIdEan, $ean){
		$req=$this->pdo->prepare("UPDATE odr_ean SET ean= :ean WHERE id= :id");
		$req->execute([
			':ean'				=>$ean,
			':id'				=>$keyIdEan
		]);
		return ($req->errorInfo());
	}
	public function updateOdr($id){
		$req=$this->pdo->prepare("UPDATE odr SET date_start= :date_start, date_end= :date_end, gt= :gt, famille= :famille, marque= :marque, by_insert= :by_insert, date_insert= :date_insert WHERE id= :id");
		$req->execute([
			':id'				=>$id,
			':date_start'		=>$_POST['date_start'],
			':date_end'			=> $_POST['date_end'],
			':gt'				=>$_POST['gt'],
			':famille'			=>$_POST['famille'],
			':marque'			=>$_POST['marque'],
			':by_insert'		=>$_SESSION['id_web_user'],
			':date_insert'		=>date('Y-m-d H:i:s')
		]);
		return ($req->errorInfo());
	}

	public function deleteEan($idOdr){
		$req=$this->pdo->prepare("DELETE FROM odr_ean WHERE id_odr= :id_odr");
		$req->execute([
			':id_odr'	=>$idOdr
		]);
	}

	public function updateNameOdrFile($id,$name){
		$req=$this->pdo->prepare("UPDATE odr_files SET filename= :filename WHERE id= :id");
		$req->execute([
			':id'				=>$id,
			':filename'			=>$name
		]);
		return ($req->errorInfo());

	}


	public function deleteOdrFile($id){
		$req=$this->pdo->prepare("DELETE FROM odr_files WHERE id= :id");
				$req->execute([
			':id'				=>$id,

		]);
	}
}
