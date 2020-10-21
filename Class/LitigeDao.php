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

	public function searchPaletteOrFacture($pdoQlik, $searchStrg, $galec){
		$req=$pdoQlik->prepare("SELECT * FROM statsventeslitiges  WHERE concat( concat('0',facture),palette) LIKE :search AND galec= :galec ORDER BY article,dossier");
		$req->execute(array(
			':search' =>'%'.$_POST['search_strg'] .'%',
			':galec'	=>$_SESSION['id_galec']
		));
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getPaletteForRobbery($pdoQlik, $arPalettes){
		$placeholders=array_fill(0, count($arPalettes), ' palette = ? OR ');
		$placeholders[count($arPalettes) -1]= 'palette = ? ';
		$placeholders=implode(' ',$placeholders);
		$req=$pdoQlik->prepare("SELECT * FROM statsventeslitiges  WHERE $placeholders ORDER BY palette, article");
		$req->execute($arPalettes);
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getBoxHead($pdoQlik, $dossier,$article){
		$req=$pdoQlik->prepare("SELECT * FROM assortiments WHERE `SCEBFAST.AST-ART`= :article AND `SCEBFAST.DOS-COD`= :dossier ");
		$req->execute(array(
			':dossier' =>$dossier,
			':article'	=>$article
		));
		return $req->fetch(PDO::FETCH_ASSOC);
	}


	function getBoxDetail($pdoQlik,$dossier, $article){
		$req=$pdoQlik->prepare("SELECT `SCEBFAST.AST-ART` as tete FROM assortiments WHERE `SCEBFAST.ART-COD`= :article AND `SCEBFAST.DOS-COD` =:dossier");
		$req->execute(array(
			':article'	=>$article,
			':dossier'	=>$dossier
		));
		return $req->fetch(PDO::FETCH_ASSOC);
	}

	public function getLitigeInfoMagById($idLitige){
		$req=$this->pdo->prepare("SELECT dossiers.id as id, dossier, magasin.mag.deno, magasin.mag.id as btlec FROM dossiers LEFT JOIN magasin.mag ON dossiers.galec=magasin.mag.galec WHERE dossiers.id= :id");
		$req->execute(array(
			':id'		=>$idLitige
		));
		return $req->fetch(PDO::FETCH_ASSOC);
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

	function getLitigesByGalec($galec){
		$req=$this->pdo->prepare("SELECT dossiers.id as id, dossier,DATE_FORMAT(date_crea,'%d-%m-%Y')as datecrea, typo, imputation, etat, tablegt.gt, valo, analyse, conclusion, mt_transp, mt_assur, mt_fourn, mt_mag, magasin.mag.deno, magasin.mag.id as btlec, magasin.mag.centrale  FROM dossiers
			LEFT JOIN magasin.mag ON dossiers.galec=magasin.mag.galec
			LEFT JOIN typo ON dossiers.id_typo=typo.id
			LEFT JOIN imputation ON dossiers.id_imputation=imputation.id
			LEFT JOIN gt as tablegt ON dossiers.id_gt=tablegt.id
			LEFT JOIN etat ON dossiers.id_etat=etat.id
			LEFT JOIN gt ON dossiers.id_gt=gt.id
			LEFT JOIN analyse ON dossiers.id_analyse=analyse.id
			LEFT JOIN conclusion ON dossiers.id_conclusion=conclusion.id
			WHERE dossiers.galec= :galec");
		$req->execute(array(
			':galec'	=>$galec
		));
		return $req->fetchAll(PDO::FETCH_ASSOC);
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

	public function getActiveAffrete(){
		$req=$this->pdo->prepare("SELECT * FROM affrete WHERE mask=0 ORDER BY affrete");
		$req->execute();
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getActiveTransporteur(){
		$req=$this->pdo->prepare("SELECT * FROM transporteur WHERE mask=0 ORDER BY transporteur");
		$req->execute();
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}


	public function getActiveTransit(){
		$req=$this->pdo->prepare("SELECT * FROM transit WHERE mask=0 ORDER BY transit");
		$req->execute();
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getActiveEquipe(){
		$req=$this->pdo->prepare("SELECT id, concat(nom, ' ', prenom) as name FROM equipe WHERE mask=0 ORDER BY  nom");
		$req->execute();
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getThisOuverture($idOuv){
		$req=$this->pdo->prepare("SELECT ouv.id, DATE_FORMAT(date_saisie, '%d-%m-%Y') as datesaisie, msg, pj, deno, magasin.mag.id as btlec, ouv.galec, ouv.etat FROM ouv LEFT JOIN magasin.mag ON ouv.galec=magasin.mag.galec WHERE ouv.id= :id");
		$req->execute(array(
			':id'		=>$idOuv
		));
		return $req->fetch(PDO::FETCH_ASSOC);
	}

	public function getOuvertureMsg($idOuv){
		$req=$this->pdo->prepare("SELECT id, id_web_user, DATE_FORMAT(date_saisie, '%d-%m-%Y') as datesaisie, msg,pj, mag FROM ouv_rep WHERE id_ouv= :id ORDER BY date_saisie");
		$req->execute(array(
			':id'		=>$idOuv
		));
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function addDetails($lastInsertId,$numDossier,$palette,	$facture,$dateFacture, $article, $ean,$dossierG, $descr, $qteC,	$tarif, $fou, $cnuf,$boxTete,$boxDetail,$occArticlePalette, $puv,$pul){
		$req=$this->pdo->prepare("INSERT INTO details_temp(id_dossier, dossier, palette, facture, date_facture, article, ean, dossier_gessica, descr, qte_cde, tarif, fournisseur, cnuf, box_tete,box_art, occ_article_palette, puv, pul) VALUES(:id_dossier, :dossier, :palette, :facture, :date_facture, :article, :ean, :dossier_gessica, :descr, :qte_cde, :tarif, :fournisseur, :cnuf, :box_tete, :box_art, :occ_article_palette, :puv, :pul)");
		$req->execute(array(
			':id_dossier'	=>$lastInsertId,
			':dossier'		=>$numDossier,
			':palette'		=>$palette,
			':facture'		=>$facture,
			':date_facture'	=>$dateFacture,
			':article'		=>$article,
			':ean'			=>$ean,
			':dossier_gessica'	=>$dossierG,
			':descr'		=>$descr,
			':qte_cde'		=> $qteC,
			':tarif'		=>$tarif,
			':fournisseur'	=>$fou,
			':cnuf'			=>$cnuf,
			':box_tete'		=>$boxTete,
			':box_art'		=>$boxDetail,
			':occ_article_palette'		=>$occArticlePalette,
			':puv'			=>$puv,
			':pul'			=>$pul
		));
	// return $req->errorInfo();
		$row=$req->rowCount();
		return	$row;
	}

	public function updateOccDossier($idDossier){
		$req=$this->pdo->prepare("UPDATE dossiers_temp SET occasion=1 WHERE id= :id");
		$req->execute([
			':id'		=>$idDossier
		]);
	}

}