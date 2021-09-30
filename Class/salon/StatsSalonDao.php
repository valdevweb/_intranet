<?php


class StatsSalonDao{

	// pdoOcc
	private $pdo;
	private $statYear;
	public function __construct($pdo, $statYear){
		$this->setPdo($pdo);
		$this->setStatYear($statYear);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function setStatYear($statYear){
		$this->statYear=$statYear;
		return $statYear;
	}


	public function getParticipantYear(){
		$table="salon_".$this->statYear;

		$req=$this->pdo->prepare("SELECT deno, $table.galec, centrale, centrale_doris, nom, prenom, fonction, DATE_FORMAT(date_saisie,'%d-%m-%Y') as datesaisie, mardi, mercredi,repas_mardi, repas_mercredi, date_passage, DATE_FORMAT(date_passage,'%H:%i') as heure FROM $table
			LEFT JOIN magasin.mag ON $table.galec=magasin.mag.galec
			LEFT JOIN magasin.sca3 ON $table.galec=magasin.sca3.galec_sca
			LEFT JOIN salon_fonction ON $table.id_fonction=salon_fonction.id
			WHERE $table.galec !='' ORDER BY magasin.mag.deno");
		$req->execute();

		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	function getNbMagInscrit(){
		$table="salon_".$this->statYear;
		$req=$this->pdo->prepare("SELECT DISTINCT(galec) FROM {$table} WHERE mask=0 ");
		$req->execute();
		return $req->fetchAll();
	}

	function getNbMagPresent(){

		$req=$this->pdo->prepare("SELECT DISTINCT galec FROM salon{$this->statYear}_mag_arrivee LEFT JOIN salon_{$this->statYear} ON id_user=salon_{$this->statYear}.id");
		$req->execute();
		return $req->fetchAll();
	}
	function getNbPresent(){

		$req=$this->pdo->prepare("SELECT DISTINCT id_user FROM salon{$this->statYear}_mag_arrivee");
		$req->execute();
		return $req->fetchAll();
	}


	function getNbPart(){
		$table="salon_".$this->statYear;
		$req=$this->pdo->prepare("SELECT sum(mardi) as p_mardi,sum(mercredi) as p_mercr,sum(repas_mardi) as repas_mardi,sum(repas_mercredi) as repas_mercr  FROM {$table}");
		$req->execute();
		return $req->fetch(PDO::FETCH_ASSOC);
	}

	function getNbMagInscritMardi(){
		$table="salon_".$this->statYear;
		$req=$this->pdo->prepare("SELECT DISTINCT(galec) FROM {$table} WHERE mardi=1 AND mask=0");
		$req->execute();
		return $req->fetchAll();
	}

	function getNbMagInscritMercredi(){
		$table="salon_".$this->statYear;
		$req=$this->pdo->prepare("SELECT DISTINCT(galec) FROM {$table} WHERE mercredi=1 AND mask=0");
		$req->execute();
		return $req->fetchAll();
	}




	function nbMagCentrale(){
		$table="salon_".$this->statYear;

		$req=$this->pdo->prepare("SELECT count(galec) as nb, centrale_doris as centrale FROM
			(SELECT DISTINCT {$table}.galec, centrale_doris FROM {$table} LEFT JOIN magasin.sca3 ON {$table}.galec=magasin.sca3.galec_sca WHERE {$table}.galec !='' AND mask=0) sousreq GROUP BY centrale");
		$req->execute();
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}



	function nbInscritFonction(){
		$table="salon_".$this->statYear;
		$req=$this->pdo->prepare("SELECT count($table.id) as nb, fonction, short FROM {$table} LEFT JOIN salon_fonction ON id_fonction=salon_fonction.id GROUP BY short ORDER BY fonction");
		$req->execute();
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}





	function getByHeure($day){
		$req=$this->pdo->query("SELECT count(id) as nb, DATE_FORMAT(datetime_arrivee, '%H') as hour, datetime_arrivee FROM (SELECT * FROM salon{$this->statYear}_mag_arrivee WHERE DATE_FORMAT(datetime_arrivee, '%d')=$day) as sousreq GROUP by DATE_FORMAT(datetime_arrivee, '%H')");
  // return $req->rowCount();
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}




}


