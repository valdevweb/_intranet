<?php

class NoteDao{

	private $pdo;



	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function getNotes($idEvo){

		$req=$this->pdo->prepare("SELECT * FROM notes WHERE id_evo = :id_evo and mask=0 ORDER BY ordre, insert_on");
		$req->execute([
			':id_evo'		=>$idEvo
		]);
		return $req->fetchAll();
	}
	public function getNote($id){

		$req=$this->pdo->prepare("SELECT * FROM notes WHERE id = :id");
		$req->execute([
			':id'		=>$id
		]);
		return $req->fetch();
	}
	public function insertNote($idEvo, $note, $ordre=null){
		if (!isset($ordre)) {
			$ordre=0;
		}
		$req=$this->pdo->prepare("INSERT INTO notes (id_evo, note, ordre, insert_on, insert_by) VALUES (:id_evo, :note, :ordre, :insert_on, :insert_by)");
		$req->execute([
			':id_evo'	=>$idEvo,
			':note'	=>$note,
			':ordre'	=>$ordre,
			':insert_on'	=>date('Y-m-d H:i:s'),
			':insert_by'=>$_SESSION['id_web_user']
		]);
		return $req->rowCount();

	}

	public function updateNote($id, $note, $ordre=null){
		if (!isset($ordre)) {
			$req=$this->pdo->prepare("UPDATE notes SET note=:note WHERE id=:id");
			$req->execute([
				':note'		=>$note,
				':id'		=>$id
			]);
		}else{
			$req=$this->pdo->prepare("UPDATE notes SET note=:note, ordre=:ordre WHERE id=:id");
			$req->execute([
				':note'		=>$note,
				':id'		=>$id,
				':ordre'	=>$ordre

			]);
		}
		return $req->rowCount();
	}

	public function maskNote($id){
		$req=$this->pdo->prepare("UPDATE notes SET mask=1 WHERE id= :id ");
		$req->execute([
			':id'		=>$id
		]);
		return $req->rowCount();
	}

}