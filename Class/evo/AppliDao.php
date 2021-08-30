<?php


class AppliDao{

	// pdoOcc
	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function getAppli($id){

		$req=$this->pdo->prepare("SELECT * FROM appli
			LEFT JOIN plateformes on id_plateforme= plateformes.id
			LEFT JOIN responsables on appli.id_resp= responsables.id
			WHERE appli.id= :id");
		$req->execute([
			':id' =>$id
		]);
		return $req->fetch();

	}


	public function getApplis(){
		$req=$this->pdo->query("SELECT * FROM appli  ORDER BY id_resp, appli");
		$data=$req->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}
	public function getApplisResp($idResp){
		$req=$this->pdo->prepare("SELECT * FROM appli  WHERE id_resp= :id_resp ORDER BY appli");
		$req->execute([
			':id_resp'	=>$idResp
		]);

		$data=$req->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}
	public function getApplisByPlateforme($idPlateforme){
		$req=$this->pdo->prepare("SELECT * FROM appli  WHERE id_plateforme= :id_plateforme ORDER BY appli");
		$req->execute([
			':id_plateforme'	=>$idPlateforme
		]);

		$data=$req->fetchAll(PDO::FETCH_ASSOC);
		if(empty($data)){
			return "";
		}
		return $data;
	}


	public function getListAppliResp($idResp){
		$req=$this->pdo->prepare("SELECT appli.*, plateforme FROM appli
			LEFT JOIN plateformes ON id_plateforme=plateformes.id

			WHERE appli.id_resp= :id_resp ORDER BY plateforme, appli");
		$req->execute([
			':id_resp'	=>$idResp
		]);

		$data=$req->fetchAll(PDO::FETCH_ASSOC);
		if(empty($data)){
			return "";
		}
		return $data;
	}


	public function getListAppliAndDocByResp($idResp){
		$req=$this->pdo->prepare("SELECT * FROM appli LEFT JOIN doc ON appli.id=doc.id_appli WHERE id_resp= :id_resp ORDER BY appli, doc_name");
		$req->execute([
			':id_resp'		=>$idResp

		]);
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function insertAppli($appli, $idPlateforme, $idResp, $path, $url){
		$req=$this->pdo->prepare("INSERT INTO appli (appli, id_plateforme, id_resp, path, url) VALUES (:appli, :id_plateforme, :id_resp, :path, :url)");
		$req->execute([
			':appli'=>$appli,
			':id_plateforme'=>$idPlateforme,
			':id_resp'=>$idResp,
			':path'=>$path,
			':url'=>$url,

		]);
		return $this->pdo->lastInsertId();
	}

	public function getDocsByAppli(){
		$req=$this->pdo->query("SELECT id_appli, doc_name, doc_link FROM docs WHERE id_module IS NULL  ORDER BY doc_name");

		return $req->fetchAll(PDO::FETCH_GROUP);
	}

	public function getDocAppli($idAppli){
		$req=$this->pdo->prepare("SELECT * FROM appli_docs WHERE id_appli= :id_appli ORDER BY filename");
		$req->execute([
			':id_appli'=>$idAppli,
		]);
		return $req->fetchAll();
	}
	public function	insertDoc($idAppli, $file, $filename, $cmt){
		$req=$this->pdo->prepare("INSERT INTO appli_docs  (id_appli, file, filename, cmt, date_insert, by_insert) VALUES (:id_appli, :file, :filename, :cmt, :date_insert, :by_insert)");
		$req->execute([
			':id_appli'			=>$idAppli,
			':file'				=>$file,
			':filename'			=>$filename,
			':cmt'				=>$cmt,
			':date_insert'		=>date('Y-m-d H:i:s'),
			':by_insert'		=>$_SESSION['id_web_user']	,

		]);
		return $req->rowCount();
	}

	public function	updateDoc($id, $file, $filename){
		$req=$this->pdo->prepare("UPDATE appli_docs  SET file=:file, filename=:filename, date_update=:date_update WHERE id=:id");
		$req->execute([
			':id'		=>$id,
			':file'		=>$file,
			':filename'		=>$filename,
			':date_update'		=>date('Y-m-d H:i:s'),

		]);
		return $req->rowCount();
	}

	public function deleteDoc($id){
		$req=$this->pdo->prepare("DELETE FROM appli_docs  WHERE id=:id");
		$req->execute([
			':id'		=>$id,

		]);
		return $req->errorInfo();
	}

}


