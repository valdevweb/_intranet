<?php

class EvoHelpers{
	public static function getIdResp($pdoEvo, $idwebuser){
		$req=$pdoEvo->prepare("SELECT id FROM responsables WHERE idwebuser= :idwebuser");
		$req->execute([
			':idwebuser'		=>$idwebuser
		]);
		$data= $req->fetch(PDO::FETCH_ASSOC);

		if(!empty($data) && isset($data['id'])){
			return $data['id'];
		}
		return "";
	}
	public static function arrayEtat($pdoEvo){
		$req=$pdoEvo->query("SELECT id, etat FROM etats ORDER BY etat");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}

	public static function arrayAppliRespName($pdoEvo){
		$req=$pdoEvo->query("SELECT id, resp FROM responsables ORDER BY id");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function arrayAppliName($pdoEvo){
		$req=$pdoEvo->query("SELECT id, appli FROM appli ORDER BY appli");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function arrayPlateformeName($pdoEvo){
		$req=$pdoEvo->query("SELECT id, plateforme FROM plateformes ORDER BY plateforme");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function arrayModuleName($pdoEvo){
		$req=$pdoEvo->query("SELECT id, module FROM modules ORDER BY module");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}

	public static function arrayAppliRespEmail($pdoEvo){
		$req=$pdoEvo->query("SELECT id, email FROM responsables ORDER BY id");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function arrayAppliRespId($pdoEvo){
		$req=$pdoEvo->query("SELECT id, id_resp FROM appli ORDER BY id");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function arrayRespName($pdoEvo){
		$req=$pdoEvo->query("SELECT id, resp FROM responsables ORDER BY resp");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}

	public static function arrayRespShort($pdoEvo){
		$req=$pdoEvo->query("SELECT id, resp_short FROM responsables ORDER BY resp");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function arrayAppliPlateformeName($pdoEvo){
		$req=$pdoEvo->query("SELECT appli.id, plateforme FROM appli LEFT JOIN plateformes ON id_plateforme=plateformes.id ORDER BY appli.id");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}

		public static function arrayAppliNameByResp($pdoEvo, $idResp){
		$req=$pdoEvo->prepare("SELECT id, appli FROM appli WHERE id_resp=id_resp ORDER BY id");
		$req->execute([
			':id_resp'	=>$idResp,
		]);
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function arrayPlateformeNameByResp($pdoEvo, $idResp){
		$req=$pdoEvo->prepare("SELECT id, plateforme FROM plateformes WHERE id_resp=id_resp ORDER BY id");
		$req->execute([
			':id_resp'	=>$idResp,
		]);
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function arrayModuleNameByResp($pdoEvo, $idResp){
		$req=$pdoEvo->prepare("SELECT id, module FROM modules LEFT JOIN appli ON id_appli= appli.id WHERE id_resp=id_resp ORDER BY id");
		$req->execute([
			':id_resp'	=>$idResp,
		]);
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}


}
