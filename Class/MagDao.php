<?php
class MagDao{

	// pdoMag
	private $pdo;
	private $galec;




	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo(PDO $pdo){
		return $this->pdo=$pdo;
	}

	public function searchMagByConcat($strg){
		$req=$this->pdo->prepare("SELECT * FROM mag WHERE concat(deno, id, galec, ville) LIKE :search ORDER BY deno");
	// $req=$pdoMag->prepare("SELECT * FROM mag WHERE concat(deno, id, galec, ville) LIKE :search ORDER BY deno");

		$req->execute([
			':search' =>'%'.$strg .'%'
		]);
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}



	public function basicSearch($strg){
		$req=$this->pdo->prepare("SELECT * FROM mag  LIKE :search");
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

	public function getMagAndScaTroisInfo($btlec){
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

	public function getMagByBtlec($btlec){
		$req=$this->pdo->prepare("SELECT * FROM mag WHERE id= :id");
		$req->execute([
			':id' =>$btlec
		]);
		$data=$req->fetch(PDO::FETCH_ASSOC);

		if(!empty($data)){
			return new Mag($data);
		}
		return false;
	}

	public function getMagAndScaTroisInfoByGalec($galec){
		$req=$this->pdo->prepare("SELECT * FROM mag  LEFT JOIN sca3 ON mag.id=sca3.btlec_sca WHERE galec= :galec");
		$req->execute([
			':galec' =>$galec
		]);
		 return $data=$req->fetch(PDO::FETCH_ASSOC);

	}
	public function getMagByGalec($galec){
		$req=$this->pdo->prepare("SELECT  * FROM mag WHERE galec= :galec");
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

	public function getMagLd($btlec,$suffixe=null){
		if($suffixe==null){
			$req=$this->pdo->prepare("SELECT * FROM lotus_ld WHERE btlec= :btlec ORDER BY suffixe, email");
			$req->execute([
				':btlec'		=>$btlec
			]);
		}else{
			$req=$this->pdo->prepare("SELECT * FROM lotus_ld WHERE btlec= :btlec AND ld_suffixe= :ld_suffixe ORDER BY email");
			$req->execute([
				':btlec'		=>$btlec,
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
		$req=$this->pdo->prepare("SELECT *, DATE_FORMAT(date_ouv, '%d/%m/%Y') as dateOuv, DATE_FORMAT(date_ferm, '%d/%m/%Y') as dateFerm FROM magsyno  LEFT JOIN mag ON magsyno.btlec_old=mag.id WHERE magsyno.galec= :galec ORDER BY date_ouv DESC ");
		$req->execute([
			':galec'		=>$galec
		]);
		$datas=$req->fetchAll(PDO::FETCH_ASSOC);
		// return $req->errorInfo();
		if(!empty($datas)){
			return $datas;
		}
		return '';
	}
	public function getMagCaByYear($pdoQlik,$btlec,$year){
		$req=$pdoQlik->prepare("SELECT CA_Annuel FROM statsventesadh WHERE CodeBtlec= :btlec AND AnneeCA= :year");
		$req->execute(array(
			':btlec' =>$btlec,
			':year'	=>$year
		));
		return $req->fetch(PDO::FETCH_ASSOC);
	}


	public function getDistinctCentraleSca(){
		// uniquement centrale pour mag de type mag
		return $req=$this->pdo->query("SELECT DISTINCT centrale_sca, centrale FROM sca3 LEFT JOIN centrales ON  sca3.centrale_sca=centrales.id_ctbt WHERE centrale_sca IS NOT NULL ORDER BY centrale")->fetchAll(PDO::FETCH_ASSOC);
	}
	public function getDistinctCentraleDoris(){
		// uniquement centrale pour mag de type mag
		return $req=$this->pdo->query("SELECT DISTINCT centrale_doris, centrale FROM sca3 LEFT JOIN centrales ON  sca3.centrale_doris=centrales.id_ctbt WHERE centrale_doris IS NOT NULL ORDER BY centrale")->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getDistinctCentraleMag(){
		// uniquement centrale pour mag de type mag
		return $req=$this->pdo->query("SELECT DISTINCT mag.centrale as id_centrale, centrales.centrale FROM mag LEFT JOIN centrales ON  mag.centrale=centrales.id_ctbt WHERE mag.centrale !=0 ORDER BY centrales.centrale")->fetchAll(PDO::FETCH_ASSOC);
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
		 return $req->fetch(PDO::FETCH_ASSOC);
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