<?php
class MagDbHelper{

	private $pdo;
	private $galec;


	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo(PDO $pdo){
		return $this->pdo=$pdo;
	}

	public function basicSearch($strg){
		$req=$this->pdo->prepare("SELECT * FROM mag  LEFT JOIN sca3 ON mag.id=sca3.btlec_sca WHERE concat(mag.deno,mag.galec,mag.id,mag.ville) LIKE :search");
		$req->execute([
			':search' =>'%'.$strg .'%'
		]);
		$datas=$req->fetchAll(PDO::FETCH_ASSOC);

		if(!empty($datas)){
			foreach ($datas as $data) {
				$mags[]= new Mag($data);

			}
			return $mags;
		}
		return false;

	}

	public function getMagBt($btlec){
		$req=$this->pdo->prepare("SELECT * FROM mag  LEFT JOIN sca3 ON mag.id=sca3.btlec_sca WHERE id= :id");
		$req->execute([
			':id' =>$btlec
		]);
		$data=$req->fetch(PDO::FETCH_ASSOC);

		if(!empty($data)){
			return new Mag($data);
		}
		return false;
	}


	public function getMagGalec($galec){
		$req=$this->pdo->prepare("SELECT * FROM mag  LEFT JOIN sca3 ON mag.id=sca3.btlec_sca WHERE mag.galec= :galec");
		$req->execute([
			':galec' =>$galec
		]);
		$data=$req->fetch(PDO::FETCH_ASSOC);

		if(!empty($data)){
			return new Mag($data);
		}
		return false;
	}

	public function centraleToString($idCtbt){
		$req=$this->pdo->prepare("SELECT id_ctbt, centrale FROM centrales WHERE id_ctbt= :id_ctbt");
		$req->execute([
			':id_ctbt'		=>$idCtbt
		]);
		$data=$req->fetch(PDO::FETCH_ASSOC);
		return $data['centrale'];
	}

	public function getMagLd($galec,$suffixe=null){
		if($suffixe==null){
			$req=$this->pdo->prepare("SELECT * FROM mag_email WHERE galec= :galec ORDER BY suffixe, email");
			$req->execute([
				':galec'		=>$galec
			]);
		}else{
			$req=$this->pdo->prepare("SELECT * FROM mag_email WHERE galec= :galec AND ld_suffixe= :ld_suffixe ORDER BY email");
			$req->execute([
				':galec'		=>$galec,
				':ld_suffixe'	=>$suffixe
			]);
		}
		$datas=$req->fetchAll(PDO::FETCH_ASSOC);
		if(!empty($datas)){
			return $datas;
		}
		return '';


	}
	public function getHisto($galec){
		$req=$this->pdo->prepare("SELECT *, DATE_FORMAT(date_ouverture, '%d/%m/%Y') as dateOuv, DATE_FORMAT(date_fermeture, '%d/%m/%Y') as dateFerm FROM magsyno  LEFT JOIN sca3 ON magsyno.btlec_old=sca3.btlec_sca WHERE galec= :galec ORDER BY date_ouverture DESC ");
		$req->execute([
			':galec'		=>$galec
		]);
		$datas=$req->fetchAll(PDO::FETCH_ASSOC);
		if(!empty($datas)){
			return $datas;
		}
		return '';

	}



	public function getDistinctCentraleSca(){
		// uniquement centrale pour mag de type mag
		return $req=$this->pdo->query("SELECT DISTINCT centrale_sca, centrale FROM sca3 LEFT JOIN centrales ON  sca3.centrale_sca=centrales.id_ctbt WHERE centrale_sca IS NOT NULL ORDER BY centrale")->fetchAll(PDO::FETCH_ASSOC);
	}
	public function getDistinctCentraleDoris(){
		// uniquement centrale pour mag de type mag
		return $req=$this->pdo->query("SELECT DISTINCT centrale_doris, centrale FROM sca3 LEFT JOIN centrales ON  sca3.centrale_doris=centrales.id_ctbt WHERE centrale_doris IS NOT NULL ORDER BY centrale")->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getListType(){
		return $req=$this->pdo->query("SELECT * FROM type ORDER BY type")->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getListTypePair(){
		return $req=$this->pdo->query("SELECT id, type FROM type")->fetchAll(PDO::FETCH_KEY_PAIR);

	}

	public function getWebUser($galec){
		$req=$this->pdo->prepare("SELECT *, web_users.users.id as id_web_user FROM mag LEFT JOIN web_users.users ON mag.galec=web_users.users.galec WHERE mag.galec= :galec");
		$req->execute([
			':galec'		=>$galec
		]);
		$datas=$req->fetchAll(PDO::FETCH_ASSOC);
		if(!empty($datas)){
			return $datas;
		}
		return "";
	}

	public function centreReiToString($centreRei){
		if($centreRei=="NULL"){
			return "";
		}
		$req=$this->pdo->prepare("SELECT centre FROM mag_rei WHERE id= :id");
		$req->execute([
			':id'		=>$centreRei
		]);
		return $req->fetch(PDO::FETCH_COLUMN);
	}
	public function getCmt($btlec){
		$req=$this->pdo->prepare("SELECT *, cmt_mag.id as idcmt, DATE_FORMAT(date_insert, '%d-%m-%Y') as dateinsert, concat(prenom, ' ',nom ) as fullname FROM cmt_mag LEFT JOIN web_users.intern_users ON created_by = web_users.intern_users.id_web_user WHERE btlec= :btlec ORDER BY date_insert DESC");
			$req->execute([
			':btlec'		=>$btlec
		]);
		$datas=$req->fetchAll(PDO::FETCH_ASSOC);
		if(!empty($datas)){
			return $datas;
		}
		return "";
	}


	public function getListCodeAcdlecUtilise(){
		$req=$this->pdo->query("SELECT acdlec_code, nom_ets  FROM mag LEFT JOIN acdlec ON acdlec_code=acdlec.code WHERE acdlec_code IS NOT NULL AND acdlec_code!='' GROUP BY acdlec_code ORDER BY acdlec_code");
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getListCodeAcdlec(){
		$req=$this->pdo->query("SELECT * FROM acdlec ORDER BY code");
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

}