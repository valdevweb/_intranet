<?php


class ModuleDao{

	// pdoOcc
	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}


	public function getListModule($idAppli){
		$req=$this->pdo->prepare("SELECT * FROM modules WHERE id_appli= :id_appli ORDER BY module");
		$req->execute([
			':id_appli'	=>$idAppli
		]);

		return $req->fetchAll(PDO::FETCH_ASSOC);

	}
	public function getModule($idModule){
		$req=$this->pdo->prepare("SELECT modules.*, appli.id_resp, appli.appli, plateforme FROM modules
			LEFT JOIN appli ON modules.id_appli=appli.id
			LEFT JOIN plateformes ON appli.id_plateforme= plateformes.id
			WHERE modules.id= :id");
		$req->execute([
			':id'	=>$idModule
		]);

		return $req->fetch(PDO::FETCH_ASSOC);

	}
	public function getModules(){
		$req=$this->pdo->query("SELECT modules.*, appli.id_resp, appli.appli, plateforme FROM modules
			LEFT JOIN appli ON modules.id_appli=appli.id
			LEFT JOIN plateformes ON appli.id_plateforme= plateformes.id
			ORDER BY  id_appli, module");
		return $req->fetchAll();
	}

	public function getModulesResp($idResp){
		$req=$this->pdo->prepare("SELECT modules.*, appli.id_resp, appli.appli, plateforme FROM modules
			LEFT JOIN appli ON modules.id_appli=appli.id
			LEFT JOIN plateformes ON appli.id_plateforme= plateformes.id
			WHERE appli.id_resp= :id_resp
			ORDER BY  id_appli, module");
		$req->execute([
			':id_resp'	=>$idResp
		]);
		return $req->fetchAll();
	}
	public function getDocModule($idModule){
		$req=$this->pdo->prepare("SELECT * FROM module_docs WHERE id_module= :id_module ORDER BY filename");
		$req->execute([
			':id_module'=>$idModule,
		]);
		return $req->fetchAll();
	}
	public function	insertDoc($idModule, $file, $filename, $cmt){
		$req=$this->pdo->prepare("INSERT INTO module_docs  (id_module, file, filename, cmt, date_insert, by_insert) VALUES (:id_module, :file, :filename, :cmt, :date_insert, :by_insert)");
		$req->execute([
			':id_module'			=>$idModule,
			':file'				=>$file,
			':filename'			=>$filename,
			':cmt'				=>$cmt,
			':date_insert'		=>date('Y-m-d H:i:s'),
			':by_insert'		=>$_SESSION['id_web_user']	,

		]);
		return $req->rowCount();
	}
public function insertModule($module, $idAppli, $descr, $url, $path){
	$req=$this->pdo->prepare("INSERT INTO modules (module, id_appli, descr, url, path) VALUES (:module, :id_appli, :descr, :url, :path)");
	$req->execute([
		':module'	=> $module,
		 ':id_appli'	=> $idAppli,
		 ':descr'	=> $descr,
		 ':url'	=> $url,
		 ':path'	=> $path,

	]);
	return $this->pdo->lastInsertId();
}

	public function deleteDoc($id){
		$req=$this->pdo->prepare("DELETE FROM module_docs  WHERE id=:id");
		$req->execute([
			':id'		=>$id,

		]);
		return $req->errorInfo();
	}


}




