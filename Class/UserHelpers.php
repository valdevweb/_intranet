<?php

class UserHelpers{




	public static function getUser($pdoUser,$id){
		$req=$pdoUser->prepare("SELECT *, concat(prenom,' ', nom) as fullname, users.id as iduser, intern_users.id as idintern FROM users LEFT JOIN intern_users ON users.id=intern_users.id_web_user WHERE users.id= :id LIMIT 1");
		$req->execute([
			':id'	=>$id
		]);
		$data=$req->fetch(PDO::FETCH_ASSOC);
		return $data;
	}

	public static function getUserByService($pdoUser,$idService){
		$req=$pdoUser->prepare("SELECT *, concat(prenom,' ', nom) as fullname FROM intern_users WHERE id_service= :id_service  ORDER BY prenom");
		$req->execute([
			':id_service'	=>$idService
		]);
		$data=$req->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}
	public static function getListUserByService($pdoUser,$idService){
		$req=$pdoUser->prepare("SELECT id_web_user, prenom FROM intern_users WHERE id_service= :id_service  ORDER BY prenom");
		$req->execute([
			':id_service'	=>$idService
		]);
		$data=$req->fetchAll(PDO::FETCH_KEY_PAIR);
		return $data;
	}



	public static function getManyUser($pdoUser,$ids){
		$arData=[];
		for ($i=0; $i < count($ids) ; $i++) {
			$data=self::getUser($pdoUser,$ids[$i]);
			array_push($arData,$data);
		}
		return $arData;
	}

	public static function getFullname($pdoUser,$id){
		$data=self::getUser($pdoUser,$id);
		return $data['fullname'];
	}

	public static function getIdService($pdoUser,$id){
		$data=self::getUser($pdoUser,$id);
		return $data['id_service'];
	}

	public static function getMobile($pdoUser,$id){
		$data=self::getUser($pdoUser,$id);
		if(!empty($data['mobile']) && $data['mobile']!=null){
			return $data['fullname'];
		}
		return false;
	}

}

