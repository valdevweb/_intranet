<?php

class MagHelpers{

	public static function magInfo($pdo, $galec){
		$req=$pdo->prepare("SELECT *,users.id as idwebuser, mag_attribution.id_web_user as id_cm FROM mag LEFT JOIN users ON mag.galec=users.galec LEFT JOIN mag_attribution ON mag.galec=mag_attribution.galec WHERE mag.galec= :galec");
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

	public static function city($pdo,$galec){
		$data=self::magInfo($pdo, $galec);
		return $data['city'];
	}

	public static function centrale($pdo,$galec){
		$data=self::magInfo($pdo, $galec);
		return $data['centrale'];
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
		// uniquement centrale pour mag de type mag
		return $req=$pdoMag->query("SELECT id_ctbt,centrale  FROM centrales ORDER BY centrales.centrale")->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public static function getListBackOffice($pdoMag){
		return $req=$pdoMag->query("SELECT id,backoffice  FROM backoffice ")->fetchAll(PDO::FETCH_KEY_PAIR);
	}
}
