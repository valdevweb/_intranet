<?php

class EvoManager{

	private $pdoEvo;



	public function __construct($pdoEvo){
		$this->setPdo($pdoEvo);
	}
	public function setPdo($pdoEvo){
		$this->pdoEvo=$pdoEvo;
		return $pdoEvo;
	}

	public function getListPlateforme(){
		$req=$this->pdoEvo->query("SELECT * FROM plateformes ORDER BY plateforme");
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}
	public function getListResp(){
		$req=$this->pdoEvo->query("SELECT * FROM responsables ORDER BY resp");
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * liste evo % param
	 * @param  [type] $pdoEvo     [pdo]
	 * @param  [type] $etat       id_etat
	 * @param  [type] $plateforme id_plateforme
	 * @param  [type] $outils      id_outils
	 * @param  [type] $module     id_module
	 * @return [array]             [list evo]
	 */

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
		$req=$this->pdoEvo->query($query);


		// $data=$req->errorInfo();
		$data=$req->fetchAll(PDO::FETCH_ASSOC);

		return	$data;
	}


	public function getListModule($idAppli){
		$req=$this->pdoEvo->prepare("SELECT * FROM modules WHERE id_appli= :id_appli ORDER BY module");
		$req->execute([
			':id_appli'	=>$idAppli
		]);

		$data=$req->fetchAll(PDO::FETCH_ASSOC);
		if(empty($data)){
			return "";
		}
		return $data;
	}

	public function getThisEvo($idEvo){
		$req=$this->pdoEvo->prepare("SELECT evos.*, plateformes.plateforme, modules.module, appli.appli, DATE_FORMAT(deadline, '%d-%M-%Y') deadlinefr, web_users.intern_users.email as mail_dd, web_users.intern_users.nom as nom_dd, web_users.intern_users.prenom as prenom_dd, web_users.intern_users.fullname as fullname_dd  FROM evos
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

	public function getListAppli($idPlateforme){
		$req=$this->pdoEvo->prepare("SELECT * FROM appli  WHERE id_plateforme= :id_plateforme ORDER BY appli");
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
		$req=$this->pdoEvo->prepare("SELECT appli.*, plateforme FROM appli
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


	public function getListEtat(){
		$req=$this->pdoEvo->query("SELECT * FROM etats");
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function updateEtat($idEvo,$etat){
			$req=$this->pdoEvo->prepare("UPDATE evos SET id_etat= :id_etat WHERE id= :id");
		$req->execute([
			':id'	=>$idEvo,
			':id_etat' => $etat
		]);
	}
	public function startEvo($idEvo,$etat){
			$req=$this->pdoEvo->prepare("UPDATE evos SET id_etat= :id_etat, date_start= :date_start WHERE id= :id");
		$req->execute([
			':id'	=>$idEvo,
			':id_etat' => $etat,
			':date_start' => date('Y-m-d H:i:s')
		]);
	}


	public function getListEvoDdeur($idFrom){

		$query="SELECT evos.*, plateforme, module, appli, resp  FROM evos
		LEFT JOIN web_users.intern_users ON id_from= web_users.intern_users.id_web_user
		LEFT JOIN plateformes ON evos.id_plateforme=plateformes.id
		LEFT JOIN modules ON evos.id_module=modules.id
		LEFT JOIN appli ON evos.id_appli=appli.id
		WHERE id_from = :id_from
		ORDER BY date_dde DESC";
		$req=$this->pdoEvo->prepare($query);
		$req->execute([
			':id_from'		=>$idFrom
		]);


		// $data=$req->errorInfo();
		$data=$req->fetchAll(PDO::FETCH_ASSOC);

		return	$data;
	}

	public function getListModuleAndDocByResp($idResp){
		$req=$this->pdoEvo->prepare("SELECT * FROM modules LEFT JOIN appli ON id_appli=appli.id LEFT JOIN doc ON module.id=doc.id_module WHERE id_resp= :id_resp ORDER BY module, doc_name");
		$req->execute([
			':id_resp'		=>$idResp

		]);
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

		public function getListAppliAndDocByResp($idResp){
		$req=$this->pdoEvo->prepare("SELECT * FROM appli LEFT JOIN doc ON appli.id=doc.id_appli WHERE id_resp= :id_resp ORDER BY appli, doc_name");
		$req->execute([
			':id_resp'		=>$idResp

		]);
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

}