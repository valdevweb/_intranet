<?php

class EvoDocDao{

	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function	getDocsEvo($idEvo){

		$req=$this->pdo->prepare("SELECT * FROM evo_docs WHERE id_evo= :id_evo order by filename");
		$req->execute([
			':id_evo'				=>$idEvo
		]);
		return $req->fetchAll();
	}

	public function	insertDoc($idEvo, $file, $filename){
		$req=$this->pdo->prepare("INSERT INTO evo_docs (id_evo, file, filename, date_insert, by_insert) VALUES (:id_evo, :file, :filename, :date_insert, :by_insert)");
		$req->execute([
			':id_evo'			=>$idEvo,
			':file'				=>$file,
			':filename'			=>$filename,
			':date_insert'		=>date('Y-m-d H:i:s'),
			':by_insert'		=>$_SESSION['id_web_user']	,

		]);
		return $req->rowCount();
	}

	public function	updateDoc($id, $file, $filename){
		$req=$this->pdo->prepare("UPDATE evo_docs SET file=:file, filename=:filename, date_update=:date_update WHERE id=:id");
		$req->execute([
			':id'		=>$id,
			':file'		=>$file,
			':filename'		=>$filename,
			':date_update'		=>date('Y-m-d H:i:s'),

		]);
		return $req->rowCount();
	}

	public function deleteDoc($id){
		$req=$this->pdo->prepare("DELETE FROM evo_docs WHERE id=:id");
		$req->execute([
			':id'		=>$id,

		]);
		return $req->errorInfo();
	}
}


