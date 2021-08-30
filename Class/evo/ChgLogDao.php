<?php


class ChgLogDao{

	// pdoOcc
	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function insertChgLog($changelog, $date, $idAppli,$idModule){
		$req=$this->pdo->prepare("INSERT INTO changelogs (changelog, date_chg, id_appli, id_module, version, date_insert) VALUES (:changelog, :date_chg, :id_appli, :id_module, :version, :date_insert)");
		$req->execute([
			':changelog'	=>$changelog,
			':date_chg'	=>$date,
			':id_appli'	=>$idAppli,
			':id_module'	=>$idModule,

			':version'	=>$_POST['version'],
			':date_insert'	=>date('Y-m-d H:i:s'),

		]);
		return $this->pdo->lastInsertId();
	}
	public function updateChgLog($idChg, $changelog, $date, $idAppli,$idModule){
		$req=$this->pdo->prepare("UPDATE changelogs SET changelog= :changelog, date_chg= :date_chg, id_appli= :id_appli, id_module= :id_module, version= :version, date_update= :date_update WHERE id= :id");
		$req->execute([
			':id'		=>$idChg,
			':changelog'	=>$changelog,
			':date_chg'	=>$date,
			':id_appli'	=>$idAppli,
			':id_module'	=>$idModule,
			':version'	=>$_POST['version'],
			':date_update'	=>date('Y-m-d H:i:s'),

		]);
		return $this->pdo->lastInsertId();
	}
	public function getChgLogsByAppli($idAppli){

		$req=$this->pdo->prepare("SELECT * FROM changelogs WHERE id_appli =:id_appli");
		$req->execute([
			':id_appli'	=>$idAppli,

		]);
		return $req->fetchAll();

	}

	public function getChgLogsByModule($idModule){

		$req=$this->pdo->prepare("SELECT * FROM changelogs WHERE id_module =:id_module");
		$req->execute([
			':id_module'	=>$idModule,

		]);
		return $req->fetchAll();

	}

	public function getChgLogsDocByAppli($idAppli){

		$req=$this->pdo->prepare("SELECT changelogs.id, changelog_files.* FROM changelogs INNER JOIN changelog_files ON changelogs.id= changelog_files.id_chg WHERE id_appli =:id_appli");
		$req->execute([
			':id_appli'	=>$idAppli,

		]);
		return $req->fetchAll(PDO::FETCH_GROUP);

	}
	public function getChgLogsDocByModule($idModule){

		$req=$this->pdo->prepare("SELECT changelogs.id, changelog_files.* FROM changelogs INNER JOIN changelog_files ON changelogs.id= changelog_files.id_chg WHERE id_module =:id_module");
		$req->execute([
			':id_module'	=>$idModule,

		]);
		return $req->fetchAll(PDO::FETCH_GROUP);

	}
	public function	insertDoc($idChg, $file, $filename){
		$req=$this->pdo->prepare("INSERT INTO changelog_files (id_chg, file, filename, date_insert, by_insert) VALUES (:id_chg, :file, :filename, :date_insert, :by_insert)");
		$req->execute([
			':id_chg'			=>$idChg,
			':file'				=>$file,
			':filename'			=>$filename,
			':date_insert'		=>date('Y-m-d H:i:s'),
			':by_insert'		=>$_SESSION['id_web_user']	,

		]);
		return $req->rowCount();
	}

	public function getChg($id){

		$req=$this->pdo->prepare("SELECT * FROM changelogs WHERE id=:id");
		$req->execute([
			':id'		=>$id
		]);
		return $req->fetch();
	}

	public function getOneChgDoc($idChg){

		$req=$this->pdo->prepare("SELECT * FROM changelog_files WHERE id_chg=:id_chg");
		$req->execute([
			':id_chg'		=>$idChg
		]);
		return $req->fetchAll();
	}


	public function deleteChgDoc($id){
		$req=$this->pdo->prepare("DELETE FROM changelog_files WHERE id=:id");
		$req->execute([
			':id'		=>$id

		]);
		return $req->errorInfo();
	}

	public function deleteChglog($id){
		$req=$this->pdo->prepare("DELETE FROM changelogs WHERE id=:id");
		$req->execute([
			':id'		=>$id

		]);
		return $req->errorInfo();
	}
}


