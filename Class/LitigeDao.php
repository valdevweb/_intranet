<?php

class LitigeDao{

	// la db est pdoLitige
	private $pdo;


	public function __construct($pdo){
		$this->setPdo($pdo);
	}

	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function getLitigeDossierDetailReclamMagEtatById($idLitige){
		$req=$this->pdo->prepare("
			SELECT
			dossiers.id as id_main,	dossiers.dossier, dossiers.date_crea, DATE_FORMAT(dossiers.date_crea, '%d-%m-%Y') as datecrea, dossiers.user_crea, dossiers.galec, dossiers.etat_dossier, dossiers.vingtquatre, dossiers.id_web_user, dossiers.nom, dossiers.valo, dossiers.flag_valo, dossiers.id_robbery, dossiers.commission,
			details.inv_palette, details.inv_qte, details.box_tete, details.box_art, details.id as id_detail, details.id_reclamation, details.ean, details.id_dossier, details.palette, details.facture, details.article, details.tarif, details.qte_cde, details.qte_litige, details.valo_line, details.dossier_gessica, details.descr, details.fournisseur, details.pj, DATE_FORMAT(details.date_facture, '%d-%m-%Y') as datefacture, details.serials, details.inversion, details.inv_article, details.inv_fournisseur, details.inv_tarif, details.inv_descr,
			reclamation.reclamation,
			magasin.mag.deno as mag, magasin.mag.centrale, magasin.mag.id as btlec,
			etat.etat
			FROM dossiers
			LEFT JOIN details ON dossiers.id=details.id_dossier
			LEFT JOIN reclamation ON details.id_reclamation = reclamation.id
			LEFT JOIN magasin.mag ON dossiers.galec=magasin.mag.galec
			LEFT JOIN etat ON etat_dossier=etat.id
			WHERE dossiers.id= :id ORDER BY date_crea");
		$req->execute(array(
			':id'	=>$idLitige
		));
		return $req->fetchAll(PDO::FETCH_ASSOC);
	// return $req->errorInfo();
	}


	public function getFirstDial($idLitige){
		$req=$this->pdo->prepare("SELECT * FROM `dial` WHERE id_dossier=:id AND mag=3");
		$req->execute(array(
			':id'	=>$idLitige
		));
		return $req->fetch(PDO::FETCH_ASSOC);
	}


	public function getInfos($idLitige){
		$req=$this->pdo->prepare("SELECT transporteur.transporteur, affrete.affrete, transit.transit, CONCAT(prepa.nom,' ', prepa.prenom) as fullprepa, CONCAT(ctrl.nom,' ',ctrl.prenom) as fullctrl,CONCAT(chg.nom,' ',chg.prenom) as fullchg, mt_transp, mt_assur, mt_fourn, mt_mag, fac_mag, DATE_FORMAT(date_prepa,'%d-%m-%Y') as dateprepa, ctrl_ok FROM dossiers
			LEFT JOIN transporteur ON id_transp=transporteur.id
			LEFT JOIN affrete ON id_affrete=affrete.id
			LEFT JOIN transit ON id_transit=transit.id
			LEFT JOIN equipe as prepa ON id_prepa=prepa.id
			LEFT JOIN equipe as ctrl ON id_ctrl=ctrl.id
			LEFT JOIN equipe as chg ON id_chg=chg.id
			LEFT JOIN equipe as ctrl_stock ON id_ctrl_stock=ctrl_stock.id
			WHERE  dossiers.id= :id ");

		$req->execute(array(
			':id'	=>$idLitige
		));
		return $req->fetch(PDO::FETCH_ASSOC);
	}


	public function getAnalyse($idLitige){
		$req=$this->pdo->prepare("SELECT gt, imputation, typo, etat, analyse, conclusion FROM dossiers
			LEFT JOIN gt ON id_gt=gt.id
			LEFT JOIN imputation ON id_imputation=imputation.id
			LEFT JOIN typo ON id_typo=typo.id
			LEFT JOIN etat ON id_etat=etat.id
			LEFT JOIN analyse ON id_analyse=analyse.id
			LEFT JOIN conclusion ON id_conclusion=conclusion.id
			WHERE dossiers.id= :id");
		$req->execute(array(
			':id'	=>$idLitige
		));
		return $req->fetch(PDO::FETCH_ASSOC);

	}

	public function getAction($idLitige){
		$req=$this->pdo->prepare("SELECT libelle, action.id_web_user, DATE_FORMAT(date_action, '%d-%m-%Y')as dateFr, concat(prenom, ' ', nom) as name, pj, action.sav, achats FROM action LEFT JOIN web_users.intern_users ON action.id_web_user=web_users.intern_users.id_web_user WHERE action.id_dossier= :id ORDER BY date_action");
		$req->execute(array(
			':id'		=>$idLitige

		));
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}


}