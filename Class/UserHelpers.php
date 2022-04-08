<?php

class UserHelpers
{


	public static function getUser($pdoUser, $id)
	{
		$req = $pdoUser->prepare("SELECT * FROM users WHERE id= :id");
		$req->execute([
			':id'	=> $id
		]);
		$data = $req->fetch(PDO::FETCH_ASSOC);
		return $data;
	}
	public static function getUserByIdWebuser($pdoUser, $idwebuser)
	{
		$req = $pdoUser->prepare("SELECT * FROM intern_users WHERE id_web_user= :id_web_user");
		$req->execute([
			':id_web_user'	=> $idwebuser
		]);
		$data = $req->fetch(PDO::FETCH_ASSOC);
		return $data;
	}
	public static function getFullnameIdwebuser($pdoUser, $idwebuser)
	{
		$data = self::getUserByIdWebuser($pdoUser, $idwebuser);
		return $data['fullname'];
	}


	public static function getMagInfoByIdWebUser($pdoUser, $pdoMag, $idwebuser, $field = null)
	{
		// prend id_web_user pour interroger table user et recup code galec
		// interroge table sca3 avec code galec
		// renvoie soit tableau complet soit juste champ demandé
		$data = self::getUser($pdoUser, $idwebuser);

		if (!empty($data['galec'])) {
			$req = $pdoMag->prepare("SELECT * FROM sca3 LEFT JOIN mag ON btlec_sca=mag.id WHERE galec_sca= :galec");
			$req->execute([
				':galec'	=> $data['galec']
			]);
			$data = $req->fetch(PDO::FETCH_ASSOC);

			if (!isset($field)) {
				return $data;
			}
			return $data[$field];
		} else {
			return "";
		}
	}


	public static function getInternUser($pdoUser, $id)
	{
		$req = $pdoUser->prepare("SELECT *, concat(prenom,' ', nom) as fullname, users.id as iduser, intern_users.id as idintern FROM users LEFT JOIN intern_users ON users.id=intern_users.id_web_user WHERE users.id= :id LIMIT 1");
		$req->execute([
			':id'	=> $id
		]);
		$data = $req->fetch(PDO::FETCH_ASSOC);
		return $data;
	}


	public static function getUserByService($pdoUser, $idService)
	{
		$req = $pdoUser->prepare("SELECT *, concat(prenom,' ', nom) as fullname FROM intern_users WHERE id_service= :id_service  ORDER BY prenom");
		$req->execute([
			':id_service'	=> $idService
		]);
		$data = $req->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}
	public static function getListUserByService($pdoUser, $idService)
	{
		$req = $pdoUser->prepare("SELECT id_web_user, prenom FROM intern_users WHERE id_service= :id_service  ORDER BY prenom");
		$req->execute([
			':id_service'	=> $idService
		]);
		$data = $req->fetchAll(PDO::FETCH_KEY_PAIR);
		return $data;
	}

	public static function listUserType($pdoUser)
	{
		$req = $pdoUser->query("SELECT id, type FROM users_type ORDER BY type");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function listDroit($pdoUser)
	{
		$req = $pdoUser->query("SELECT id, fonction FROM droits ORDER BY id");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function listService($pdoUser)
	{
		$req = $pdoUser->query("SELECT id, service FROM services ORDER BY service");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function listServiceGroup($pdoUser)
	{
		$req = $pdoUser->query("SELECT id, groupe FROM services_groupe ORDER BY groupe");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function getLdGt($pdoUser, $gt)
	{
		$req = $pdoUser->prepare("SELECT * FROM gts_ld WHERE id_gt= :id_gt");
		$req->execute([
			':id_gt'	=> $gt
		]);
		return $req->fetchAll();
	}


	public static function getManyUser($pdoUser, $ids)
	{
		$arData = [];
		for ($i = 0; $i < count($ids); $i++) {
			$data = self::getInternUser($pdoUser, $ids[$i]);
			array_push($arData, $data);
		}
		return $arData;
	}

	public static function getFullname($pdoUser, $id)
	{
		$data = self::getInternUser($pdoUser, $id);
		if (!empty($data)) {
			return $data['fullname'];
		}
		return "";
	}

	public static function getPrenom($pdoUser, $id)
	{
		$data = self::getInternUser($pdoUser, $id);
		if (!empty($data)) {
			return $data['prenom'];
		}
		return "";
	}
	public static function getIdService($pdoUser, $id)
	{
		$data = self::getInternUser($pdoUser, $id);
		if (!empty($data)) {
			return $data['id_service'];
		}
		return "";
	}

	public static function getMobile($pdoUser, $id)
	{
		$data = self::getInternUser($pdoUser, $id);
		if (!empty($data['mobile']) && $data['mobile'] != null) {
			return $data['fullname'];
		}
		return false;
	}

	public static function isUserAllowed($pdoUser, $params)
	{
		$session = $_SESSION['id'];
		$placeholders = implode(',', array_fill(0, count($params), '?'));
		$req = $pdoUser->prepare("SELECT id_user FROM attributions WHERE id_droit IN($placeholders) AND id_user=$session");
		$req->execute($params);
		$datas = $req->fetchAll(PDO::FETCH_ASSOC);
		if (empty($datas)) {
			return false;
		}
		return true;
	}
}
