<?php

class MagHelpers{


	/*

	WARNING UTILISER PDOMAG


	 */

	public static function magInfo($pdo, $galec){
		$req=$pdo->prepare("SELECT mag.*,users.id as idwebuser FROM mag LEFT JOIN web_users.users ON mag.galec=web_users.users.galec  WHERE mag.galec= :galec");
		$req->execute([
			':galec'		=>$galec
		]);
		return $req->fetch(PDO::FETCH_ASSOC);
	}

	public static function deno($pdo,$galec){
		if($galec!=''){
			$data=self::magInfo($pdo, $galec);
			return $data['deno'];
		}
		return false;
	}

	public static function ville($pdo,$galec){
		$data=self::magInfo($pdo, $galec);
		return $data['ville'];
	}

	public static function centrale($pdo,$galec){
		$data=self::magInfo($pdo, $galec);
		return $data['centrale'];
	}

	public static function centraleName($pdo,$galec){
		$data=self::magInfo($pdo, $galec);
		$req=$pdo->query("SELECT id_ctbt, centrale FROM centrales ORDER BY id_ctbt");
		$centraleList=$req->fetchAll(PDO::FETCH_KEY_PAIR);

		return $centraleList[$data['centrale']];
	}

	public static function btlec($pdo,$galec){
		$data=self::magInfo($pdo, $galec);
		return $data['btlec'];
	}

	public static function tel($pdo,$galec){
		$data=self::magInfo($pdo, $galec);
		return $data['tel'];
	}
	public static function surface($pdo,$galec){
		$data=self::magInfo($pdo, $galec);
		return $data['surface'];
	}

	public static function adherent($pdo,$galec){
		$data=self::magInfo($pdo, $galec);
		return $data['adherent'];
	}
	public static function idwebuser($pdo,$galec){
		$data=self::magInfo($pdo, $galec);
		return $data['idwebuser'];
	}

	public static function fulladresse($pdo,$galec,$separateur){
		$data=self::magInfo($pdo, $galec);
		$address=$data['ad1'];
		$address.= (!empty($data['ad2'])) ? $separateur .$data['ad2'] :'';
		$address.= (!empty($data['ad3'])) ? $separateur .$data['ad3'] :'';
		$address.= (!empty($data['cp'])) ? $separateur .$data['cp'] :'';
		$address.= (!empty($data['city'])) ? $separateur .$data['city'] :'';

		return $address;
	}
	public static function getListCentrale($pdoMag){
		return $req=$pdoMag->query("SELECT id_ctbt,centrale  FROM centrales WHERE main=1 ORDER BY centrales.centrale")->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function getListCentraleStats($pdoMag){
		return $req=$pdoMag->query("SELECT id_ctbt,centrale  FROM centrales WHERE stats=1 ORDER BY centrales.centrale")->fetchAll(PDO::FETCH_KEY_PAIR);
	}

	public static function getListBackOffice($pdoMag){
		return $req=$pdoMag->query("SELECT id,backoffice  FROM backoffice ")->fetchAll(PDO::FETCH_KEY_PAIR);
	}


}
