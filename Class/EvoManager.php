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
	public function getListEvo($etat=null,$plateforme=null,$outils=null,$module=null){
		$params="";

		if(is_null($etat)){
			$params="";
		}else{
			$paramList[]='id_etat= '.$etat;

			if(!is_null($plateforme)){
				$paramList[]='evos.id_plateforme= '.$plateforme;
			}
			if(!is_null($outils)){
				$paramList[]='evos.id_outils= '.$outils;
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
		$query="SELECT evos.*, plateforme, module, outils, id_web_user, CONCAT(prenom, ' ', nom) as ddeur FROM evos
		LEFT JOIN web_users.intern_users ON id_from= web_users.intern_users.id_web_user
		LEFT JOIN plateformes ON evos.id_plateforme=plateformes.id
		LEFT JOIN modules ON evos.id_module=modules.id
		LEFT JOIN outils ON evos.id_outils=outils.id $params ORDER BY date_dde DESC";
		$req=$this->pdoEvo->query($query);


		// $data=$req->errorInfo();
		$data=$req->fetchAll(PDO::FETCH_ASSOC);

		return	$data;
	}


	public function getListModule($idOutils){
		$req=$this->pdoEvo->prepare("SELECT * FROM modules WHERE id_outils= :id_outils ORDER BY module");
		$req->execute([
			':id_outils'	=>$idOutils
		]);

		$data=$req->fetchAll(PDO::FETCH_ASSOC);
		if(empty($data)){
			return "";
		}
		return $data;
	}

	public function getListOutils($idPlateforme){
		$req=$this->pdoEvo->prepare("SELECT * FROM outils WHERE id_plateforme= :id_plateforme ORDER BY outils");
		$req->execute([
			':id_plateforme'	=>$idPlateforme
		]);

		$data=$req->fetchAll(PDO::FETCH_ASSOC);
		if(empty($data)){
			return "";
		}
		return $data;
	}

	public function getListOutilsResp($idResp){
		$req=$this->pdoEvo->prepare("SELECT outils.*, plateforme FROM outils LEFT JOIN plateformes ON id_plateforme=plateformes.id WHERE outils.id_resp= :id_resp ORDER BY plateforme, outils");
		$req->execute([
			':id_resp'	=>$idResp
		]);

		$data=$req->fetchAll(PDO::FETCH_ASSOC);
		if(empty($data)){
			return "";
		}
		return $data;
	}

}