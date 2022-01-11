<?php


class CdesAchatDao{

	// pdoOcc
	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}
	public function insertInfos($idImport,$idEncours, $date, $qte,$cmt){
		$week=null;
		if($date!=null){
			$week=(new DateTime($date))->format('W');
		}
		$req=$this->pdo->prepare("INSERT INTO cdes_infos (id_encours, id_import, date_previ, week_previ, qte_previ, cmt, date_insert, id_web_user) VALUES (:id_encours, :id_import, :date_previ, :week_previ, :qte_previ, :cmt, :date_insert, :id_web_user)");
		$req->execute([
			':id_encours'	=>$idEncours,
			':id_import'	=>$idImport,
			':date_previ'	=>$date,
			':week_previ'	=>$week,
			':qte_previ'	=>$qte,
			':cmt'	=>$cmt,
			':date_insert'		=>date('Y-m-d H:i:s'),
			':id_web_user'	=>$_SESSION['id_web_user'],

		]);
		return $req->rowCount();
	}

	public function getInfos($param=null){
		if($param==null){
			$param="";
		}
		$query="SELECT cdes_infos.id_encours, cdes_infos.* 
		FROM cdes_infos 
		LEFT JOIN qlik.cdes_encours ON id_encours=qlik.cdes_encours.id
		WHERE (date_cde IS NOT NULL AND qte_cde !=0 AND del=0)  and date_previ >= :date_previ  $param ORDER BY date_cde";
		// echo $query;
		$req=$this->pdo->prepare($query);
		$req->execute([
			':date_previ'	=>date('Y-m-d')
		]);

		return $req->fetchAll(PDO::FETCH_GROUP);
	}

	public function getInfosOpRelances($dateStart, $dateEnd, $param=null){
		if($param==null){
			$param="";
		}
		$oneweekOld=(new DateTime())->modify('- 7 day');

		$req=$this->pdo->prepare("
			SELECT cdes_infos.id_encours, cdes_infos.* FROM cdes_infos LEFT JOIN qlik.cdes_encours ON id_encours=qlik.cdes_encours.id
			WHERE date_cde IS NOT NULL AND date_cde < :oneweekold AND qte_cde !=0 AND del=0 AND date_start BETWEEN :date_start AND :date_end  $param ORDER BY fournisseur, date_cde");
		$req->execute([
			':oneweekold'		=>$oneweekOld->format('Y-m-d'),
			':date_start'		=>$dateStart->format('Y-m-d'),
			':date_end'		=>$dateEnd->format('Y-m-d')
		]);
		return $req->fetchAll(PDO::FETCH_GROUP);
	}
	public function getInfosOpRelancesWithDatePrevi($dateStart, $dateEnd, $param=null){
		if($param==null){
			$param="";
		}
		$oneweekOld=(new DateTime())->modify('- 7 day');

		$req=$this->pdo->prepare("
			SELECT cdes_infos.id_encours, cdes_infos.* FROM cdes_infos LEFT JOIN qlik.cdes_encours ON id_encours=qlik.cdes_encours.id
			WHERE date_previ IS NOT NULL and  date_previ > :today AND date_cde IS NOT NULL AND date_cde < :oneweekold AND qte_cde !=0 AND del=0 AND date_start BETWEEN :date_start AND :date_end  $param ORDER BY fournisseur, date_cde");
		$req->execute([
			':today'			=>date('Y-m-d'),
			':oneweekold'		=>$oneweekOld->format('Y-m-d'),
			':date_start'		=>$dateStart->format('Y-m-d'),
			':date_end'		=>$dateEnd->format('Y-m-d')
		]);
		return $req->fetchAll(PDO::FETCH_GROUP);
	}



	public function getInfosOpPerm($param=null){
		if($param==null){
			$param="";
		}
		$req=$this->pdo->prepare("
			SELECT cdes_infos.id_encours, cdes_infos.* FROM cdes_infos LEFT JOIN qlik.cdes_encours ON id_encours=qlik.cdes_encours.id
			WHERE date_cde IS NOT NULL AND dossier=1000 AND qte_cde !=0  $param ORDER BY fournisseur, date_cde");
		$req->execute([

		]);
		return $req->fetchAll(PDO::FETCH_GROUP);
	}

	public function getInfosOpPermWithDatePrevi($param=null){
		if($param==null){
			$param="";
		}
		$req=$this->pdo->prepare("
			SELECT cdes_infos.id_encours, cdes_infos.* FROM cdes_infos LEFT JOIN qlik.cdes_encours ON id_encours=qlik.cdes_encours.id
			WHERE date_previ IS NOT NULL AND date_cde IS NOT NULL AND dossier=1000 AND qte_cde !=0  $param ORDER BY fournisseur, date_cde");
		$req->execute([

		]);
		return $req->fetchAll(PDO::FETCH_GROUP);
	}

	public function getInfosIdEncours($param){

		$req=$this->pdo->query("
			SELECT cdes_infos.id_encours, cdes_infos.* FROM cdes_infos WHERE del=0 $param ORDER BY id_encours, date_previ");
		return $req->fetchAll(PDO::FETCH_GROUP);
	}

	public function getInfoIdEncours($idEncours){

		$req=$this->pdo->prepare("
			SELECT * FROM cdes_infos WHERE del=0 AND  id_encours= :id_encours ORDER BY id");
		$req->execute([
			':id_encours'	=>$idEncours,

		]);
		return $req->fetchAll();
	}


	public function maskInfo($id){
		$req=$this->pdo->prepare("UPDATE cdes_infos SET del=1, id_web_user= :id_web_user, date_update= :date_update WHERE id= :id");
		$req->execute([
			':id'		=>$id,
			':id_web_user'	=>$_SESSION['id_web_user'],
			':date_update'	=>date('Y-m-d H:i:s')
		]);
		return $req->errorInfo();
	}
	public function getInfo($id){
		$req=$this->pdo->prepare("SELECT * FROM cdes_infos WHERE id=:id");
		$req->execute([
			':id'		=>$id
		]);
		return $req->fetch();
	}
	public function updateInfo($id, $date, $qte,$cmt){
		$week=null;
		if($date!=null){
			$week=(new DateTime($date))->format('W');
		}
		$req=$this->pdo->prepare("UPDATE cdes_infos SET date_previ= :date_previ, week_previ= :week_previ, qte_previ= :qte_previ, cmt=:cmt, id_web_user= :id_web_user, date_update= :date_update WHERE id= :id");
		$req->execute([
			':id'		=>$id,
			':date_previ'	=>$date,
			':week_previ'	=>$week,
			':qte_previ'	=>$qte,
			':cmt'	=>$cmt,
			':id_web_user'	=>$_SESSION['id_web_user'],
			':date_update'	=>date('Y-m-d H:i:s')
		]);
		return $req->errorInfo();
	}
	public function insertImport($file, $idwebuser){
		$req=$this->pdo->prepare("INSERT INTO cdes_imports (file, date_import, by_import) VALUES (:file, :date_import, :by_import)");
		$req->execute([
			':file'		=>$file,
			':date_import'	=>date('Y-m-d H:i:s'),
			':by_import'	=>$idwebuser
		]);
		return $this->pdo->lastInsertId();
	}
	public function getInfoByImport($idImport){

		$req=$this->pdo->prepare("SELECT cdes_infos.*, qlik.cdes_encours.* FROM cdes_infos LEFT join qlik.cdes_encours ON id_encours= qlik.cdes_encours.id WHERE id_import=:id_import");
		$req->execute([
			':id_import'	=>$idImport
		]);
		return $req->fetchAll();
	}


}


