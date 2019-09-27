<?php

class MagHelpers{

	public static function magInfo($pdo, $galec){
		$req=$pdo->prepare("SELECT *,users.id as idwebuser FROM mag LEFT JOIN users ON mag.galec=users.galec WHERE mag.galec= :galec");
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
}
