<?php 

class OccCdesDao{

	// la db est pdoLitige
	private $pdo;


	public function __construct($pdo){
		$this->setPdo($pdo);
	}

	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}


	public function addToTemp($pdoOcc){
		$req=$this->pdo->prepare("INSERT INTO cdes_temp (id_web_user, id_palette, date_insert) VALUES (:id_web_user, :id_palette, :date_insert) ");
		$req->execute([
			':id_web_user'		=>$_SESSION['id_web_user'],
			':id_palette'		=>$_POST['id_palette'],
			':date_insert'		=>date('Y-m-d H:i:s')

		]);

		$err=$req->errorInfo();


		if(!empty($err[2])){
			return false;
		}
		return true;
	}

	public function getNbPaletteCdeByMagImport($idImport){
			$req=$this->pdo->prepare("SELECT * FROM import_excel 
				LEFT JOIN palettes ON import_excel.id=palettes.import 
				LEFT JOIN cdes_detail ON palettes.id=cdes_detail.id_palette
				WHERE import_excel.id= :id_import AND cdes_detail.id_web_user = :id_web_user");
		$req->execute([
			':id_import'	=>$idImport,
			':id_web_user'	=>$_SESSION['id_web_user']


		]);
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}
	public function getNbPaletteCdeTempByMagImport($idImport){
			$req=$this->pdo->prepare("SELECT * FROM import_excel 
				LEFT JOIN palettes ON import_excel.id=palettes.import 
				LEFT JOIN cdes_temp ON palettes.id=cdes_temp.id_palette
				WHERE import_excel.id= :id_import AND cdes_temp.id_web_user = :id_web_user");
		$req->execute([
			':id_import'	=>$idImport,
			':id_web_user'	=>$_SESSION['id_web_user']

		]);
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}
}