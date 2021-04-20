<?php


class CdesDao{

	// pdoOcc
	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

// 	public function getCdes($param=null){
// 		if($param==null){
// 			$param="";
// 		}
// 		$req=$this->pdo->query("
// 			SELECT
// *

// 			cdes_fou.gt,	cdes_fou.id as num_cde,	cdes_fou.date_cde,
// 			cdes_fou_details.cond_carton, cdes_fou_details.qte_cde, cdes_fou_details.qte_uv_cde,
// 			ba.fournisseur,	ba.article,	ba.dossier,	ba.ref,	ba.libelle as libelle_art, ba.marque, cata_dossiers.date_start,	cata_dossiers.date_end,	cata_dossiers.libelle as libelle_op,
// 			qte_cde_init
// 			FROM cdes_fou
// 			LEFT JOIN cdes_fou_details  ON cdes_fou_details.id_cde=cdes_fou.id
// 			LEFT JOIN cdes_fou_qte_init ON cdes_fou_details.id=cdes_fou_qte_init.id_detail
// 			LEFT JOIN ba ON id_artdos=ba.id
// 			LEFT JOIN cata_dossiers ON cdes_fou_details.dossier=cata_dossiers.dossier
// 			WHERE cdes_fou.date_cde IS NOT NULL AND qte_cde !=0  $param ORDER BY cdes_fou.date_cde, cdes_fou.id");
// 		return $req->fetchAll();
// 	}

	public function getCdes($param=null){
		if($param==null){
			$param="";
		}
		$req=$this->pdo->query("
			SELECT * FROM cdes_encours
			WHERE date_cde IS NOT NULL AND qte_cde !=0  $param ORDER BY date_cde");
		return $req->fetchAll();
	}

	public function getEncours($id){
		$req=$this->pdo->prepare("SELECT * FROM cdes_encours  WHERE id= :id");
		$req->execute([
			':id'		=>$id
		]);
		return $req->fetch();
	}
	public function getEncoursByIds($param){
		$req=$this->pdo->query("SELECT cnuf, cdes_encours.* FROM cdes_encours  $param ORDER BY fournisseur, ref");
		return $req->fetchAll();
	}
	public function getEncoursByIdsGroup($param){
		$req=$this->pdo->query("SELECT cnuf, cdes_encours.* FROM cdes_encours  $param ORDER BY fournisseur, ref");
		return $req->fetchAll(PDO::FETCH_GROUP);
	}
	public function getEncoursCnufByIds($param){
		$req=$this->pdo->query("SELECT cnuf, fournisseur FROM cdes_encours  $param GROUP BY cnuf");
		return $req->fetchAll();
	}

	public function getEncoursFouByIds($param){
		$req=$this->pdo->query("SELECT fournisseur, cnuf FROM cdes_encours  $param GROUP BY cnuf ORDER BY fournisseur");
		return $req->fetchAll();
	}


	public function getCdesOpRelances($dateStart, $dateEnd, $param=null){
		if($param==null){
			$param="";
		}

		$oneweekOld=(new DateTime())->modify('- 7 day');
			$req=$this->pdo->prepare("
			SELECT * FROM cdes_encours WHERE date_cde IS NOT NULL AND date_cde < :oneweekold AND qte_cde !=0 AND date_start BETWEEN :date_start AND :date_end $param ORDER BY fournisseur, date_cde");
		$req->execute([
			':oneweekold'		=>$oneweekOld->format('Y-m-d'),
			':date_start'		=>$dateStart->format('Y-m-d'),
			':date_end'		=>$dateEnd->format('Y-m-d')
		]);
		return $req->fetchAll();
		// return $req->fetch(PDO::FETCH_LAZY);
	}

	public function getCdesOpRelancesGroupByCnuf($dateStart, $dateEnd, $param=null){
		if($param==null){
			$param="";
		}

		$oneweekOld=(new DateTime())->modify('- 7 day');
			$req=$this->pdo->prepare("
			SELECT  cnuf, cdes_encours.*  FROM cdes_encours WHERE date_cde IS NOT NULL AND date_cde < :oneweekold AND qte_cde !=0 AND date_start BETWEEN :date_start AND :date_end $param ORDER BY fournisseur, date_cde");
		$req->execute([
			':oneweekold'		=>$oneweekOld->format('Y-m-d'),
			':date_start'		=>$dateStart->format('Y-m-d'),
			':date_end'		=>$dateEnd->format('Y-m-d')
		]);
		return $req->fetchAll(PDO::FETCH_GROUP);
		// return $req->fetch(PDO::FETCH_LAZY);
	}



	public function getCdesPermRelances($param){
		if($param==null){
			$param="";
		}
		$oneweekOld=(new DateTime())->modify('- 7 day');

		$req=$this->pdo->prepare("
			SELECT * FROM cdes_encours WHERE date_cde IS NOT NULL AND date_cde < :oneweekold AND dossier=1000 AND qte_cde !=0 $param ORDER BY fournisseur, date_cde");
		$req->execute([
			':oneweekold'		=>$oneweekOld->format('Y-m-d'),

		]);
		return $req->fetchAll();
		// return $req->fetch(PDO::FETCH_LAZY);
	}
public function getCdesPermRelancesGroupByCnuf($param){
		if($param==null){
			$param="";
		}
		$oneweekOld=(new DateTime())->modify('- 7 day');

		$req=$this->pdo->prepare("
			SELECT  cnuf, cdes_encours.*  FROM cdes_encours WHERE date_cde IS NOT NULL AND date_cde < :oneweekold AND dossier=1000 AND qte_cde !=0 $param ORDER BY fournisseur, date_cde");
		$req->execute([
			':oneweekold'		=>$oneweekOld->format('Y-m-d'),

		]);
		return $req->fetchAll(PDO::FETCH_GROUP);
		// return $req->fetch(PDO::FETCH_LAZY);
	}

	public function getDateLivToday($param=null){
		if($param==null){
			$param="";
		}

		$req=$this->pdo->prepare("SELECT * FROM cdes_encours WHERE date_liv= :date_liv AND qte_cde !=0 AND dossier= 1000 $param  ORDER BY fournisseur");
		$req->execute([
				':date_liv'	=>date('Y-m-d')
		]);
		return $req->fetchAll();
	}
}


