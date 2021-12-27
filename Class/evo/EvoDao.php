<?php

class EvoDao{

	private $pdo;



	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function getListResp(){
		$req=$this->pdo->query("SELECT * FROM responsables ORDER BY resp");
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getRespId($idwebuser){
		$req=$this->pdo->prepare("SELECT * FROM responsables WHERE idwebuser= :id_web_user ORDER BY resp");
		$req->execute([
			':id_web_user'	=>$idwebuser
		]);
		return $req->fetch(PDO::FETCH_ASSOC);
	}

	public function getListEvo($etat=null,$plateforme=null,$appli=null,$module=null){
		$params="";

		if(is_null($etat)){
			$params="";
		}else{
			$paramList[]='id_etat= '.$etat;

			if(!is_null($plateforme)){
				$paramList[]='evos.id_plateforme= '.$plateforme;
			}
			if(!is_null($appli)){
				$paramList[]='evos.id_appli= '.$appli;
			}
			if(!is_null($module)){
				$paramList[]='evos.id_module= '.$module;
			}
		}
		if(isset($paramList)){
			$paramList=array_filter($paramList);
			$params=implode(' AND ',$paramList);
			$params= " WHERE " .$params;
		}
		$query="SELECT evos.*, plateforme, module, appli, id_web_user, CONCAT(prenom, ' ', nom) as ddeur,  responsables.email as dev_mail, web_users.intern_users.email as dd_mail  FROM evos
		LEFT JOIN web_users.intern_users ON id_from= web_users.intern_users.id_web_user
		LEFT JOIN plateformes ON evos.id_plateforme=plateformes.id
		LEFT JOIN modules ON evos.id_module=modules.id
		LEFT JOIN appli ON evos.id_appli=appli.id $params ORDER BY date_dde DESC";
		$req=$this->pdo->query($query);


		// $data=$req->errorInfo();
		$data=$req->fetchAll(PDO::FETCH_ASSOC);

		return	$data;
	}



	public function getThisEvo($idEvo){
		$req=$this->pdo->prepare("SELECT evos.*, plateformes.plateforme, modules.module, appli.appli, DATE_FORMAT(deadline, '%d-%M-%Y') deadlinefr, web_users.intern_users.email as mail_dd, web_users.intern_users.nom as nom_dd, web_users.intern_users.prenom as prenom_dd, web_users.intern_users.fullname as fullname_dd  FROM evos
			LEFT JOIN plateformes on evos.id_plateforme=plateformes.id
			LEFT JOIN appli  on evos.id_appli= appli.id
			LEFT JOIN modules on evos.id_module=modules.id
			LEFT JOIN web_users.intern_users on id_from=web_users.intern_users.id_web_user
			WHERE evos.id= :id ");
		$req->execute([
			':id'		=>$idEvo
		]);
		return $req->fetch(PDO::FETCH_ASSOC);

	}

	public function insertEvo($resp){

		$req=$this->pdo->prepare("INSERT INTO evos (id_from, id_resp, objet, evo, id_etat, date_dde, id_chrono, id_plateforme, id_appli, id_module)
			VALUES (:id_from, :id_resp, :objet, :evo, :id_etat, :date_dde, :id_chrono, :id_plateforme, :id_appli, :id_module)");
		$req->execute([
			':id_from'		=>$_SESSION['id_web_user'],
			':id_resp'		=>$resp,
			':objet'		=>$_POST['objet'],
			':evo'		=>$_POST['evo'],
			':id_etat'		=>1,
			':date_dde'		=>date('Y-m-d H:i:s'),
			':id_chrono'		=>$_POST['chrono'],
			':id_plateforme'		=>$_POST['pf'],
			':id_appli'		=>$_POST['appli'],
			':id_module'		=>empty($_POST['module'])? null: $_POST['module']

		]);

		return $this->pdo->lastInsertId();

	}



	public function getListEtat(){
		$req=$this->pdo->query("SELECT * FROM etats");
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function updateEtat($idEvo,$etat){
		$req=$this->pdo->prepare("UPDATE evos SET id_etat= :id_etat WHERE id= :id");
		$req->execute([
			':id'	=>$idEvo,
			':id_etat' => $etat
		]);
	}
	public function startEvo($idEvo,$etat){
		$req=$this->pdo->prepare("UPDATE evos SET id_etat= :id_etat, date_start= :date_start WHERE id= :id");
		$req->execute([
			':id'	=>$idEvo,
			':id_etat' => $etat,
			':date_start' => date('Y-m-d H:i:s')
		]);
	}


	public function getListEvoUser($idUser){

		$query="SELECT evos.*, plateforme, module, appli, resp  FROM evos
		LEFT JOIN web_users.intern_users ON id_from= web_users.intern_users.id_web_user
		LEFT JOIN plateformes ON evos.id_plateforme=plateformes.id
		LEFT JOIN modules ON evos.id_module=modules.id
		LEFT JOIN appli ON evos.id_appli=appli.id
		LEFT JOIN affectations ON evos.id=affectations.id_evo
		WHERE id_from = :id_from OR affectations.id_web_user= :id_from GROUP BY evos.id
		ORDER BY appli.appli, modules.module DESC";
		$req=$this->pdo->prepare($query);
		$req->execute([
			':id_from'		=>$idUser
		]);


		// $data=$req->errorInfo();
		$data=$req->fetchAll(PDO::FETCH_ASSOC);

		return	$data;
	}

	public function getListModuleAndDocByResp($idResp){
		$req=$this->pdo->prepare("SELECT * FROM modules LEFT JOIN appli ON id_appli=appli.id LEFT JOIN doc ON module.id=doc.id_module WHERE id_resp= :id_resp ORDER BY module, doc_name");
		$req->execute([
			':id_resp'		=>$idResp

		]);
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function addPlateform(){
		$req=$this->pdo->prepare("INSERT INTO plateformes (id_resp, plateforme) VALUES (:id_resp, :plateforme)");
		$req->execute([
			':id_resp'		=>$_POST['id_resp'],
			':plateforme'	=>$_POST['plateforme']

		]);
		return $req->rowCount();
	}

	public function getPlateforme($id){
		$req=$this->pdo->prepare("SELECT * FROM plateformes WHERE id= :id");
		$req->execute([
			':id'	=>$id
		]);
		return $req->fetch();
	}

	public function updatePlateforme($id){
		$req=$this->pdo->prepare("UPDATE plateformes SET id_resp=:id_resp, plateforme= :plateforme WHERE id= :id");
		$req->execute([
			':id'		=>$id,
			':plateforme'		=>$_POST['plateforme'],
			':id_resp'			=>$_POST['id_resp']
		]);
		return $req->rowCount();
	}


	public function	insertDocTable($table, $idName, $id, $file, $filename, $cmt, $url, $urlname){
		$req=$this->pdo->prepare("INSERT INTO {$table} ({$idName}, file, filename, cmt, url, urlname, date_insert, by_insert) VALUES (:id_name, :file, :filename, :cmt, :url, :urlname, :date_insert, :by_insert)");
		$req->execute([
			':id_name'			=>$id,
			':file'				=>$file,
			':filename'			=>$filename,
			':cmt'			=>$cmt,
			':url'			=>$url,
			':urlname'			=>$urlname,
			':date_insert'		=>date('Y-m-d H:i:s'),
			':by_insert'		=>$_SESSION['id_web_user']	,

		]);
		return $req->errorInfo();
	}

	public function updateEvo($idEvo, $evo, $cmtDd, $idChono){
		$req=$this->pdo->prepare("UPDATE evos SET evo= :evo, cmt_dd= :cmt_dd, id_chrono= :id_chrono WHERE id= :id");
		$req->execute([
			':id'		=>$idEvo,
			':evo'		=>$evo,
			':cmt_dd'		=>$cmtDd,
			'id_chrono'		=>$idChono

		]);
		return $req->rowCount();
	}

	public function getEvoParam($param){
		$req=$this->pdo->query("SELECT * FROM evos WHERE {$param}");
		return $req->fetchAll();
	}
	public function getEvoByModule($idModule){

		$req=$this->pdo->prepare("SELECT * FROM evos WHERE id_module= :id_module ORDER BY date_dde DESC");
		$req->execute([
			'id_module'		=>$idModule
		]);
		return $req->fetchAll();
	}


	public function statuer(){

		$req=$this->pdo->prepare("UPDATE evos SET id_etat= :id_etat ,cmt_dd= :cmt_dd, cmt_dev= :cmt_dev, date_validation= :date_validation, deadline= :deadline WHERE id= :id");
		$req->execute([
			':id'		=>$_POST['id_evo'],
			':id_etat'		=>$_POST['statut'],
			':cmt_dd'		=>$_POST['cmt_dd'],
			":cmt_dev"		=>$_POST['cmt_dev'],
			':date_validation'	=>date('Y-m-d H:i:s'),
			':deadline'			=>!empty($_POST['deadline'])? $_POST['deadline'] : NULL,


		]);
		return $req->errorInfo();
	}

	public function getEvoNoPlanning($idwebuser,$etat){

		$req=$this->pdo->prepare("SELECT evos.* FROM evos
			LEFT JOIN planning ON evos.id= planning.id_evo
			LEFT JOIN responsables ON evos.id_resp= responsables.id
			WHERE idwebuser= :idwebuser AND id_etat = :id_etat AND planning.date_start IS NULL GROUP BY evos.id ORDER BY evos.id desc");
		$req->execute([
			':id_etat'	=>$etat,
			':idwebuser'		=>$idwebuser

		]);
		return $req->fetchAll();
	}

	public function endEvo(){
		$req=$this->pdo->prepare("UPDATE evos SET cmt_end_dd= :cmt_end_dd, cmt_end_resp= :cmt_end_resp, date_end= :date_end, id_etat= :id_etat WHERE id= :id");
		$req->execute([
			':cmt_end_dd'		=>$_POST['cmt_dd'],
			':cmt_end_resp'		=>$_POST['cmt_resp'],
			':date_end'		=>date('Y-m-d H:i:s'),
			':id_etat'		=>4,
			':id'		=>$_POST['id_evo'],

		]);
		return $req->errorInfo();
		// return $req->rowCount();

	}


}