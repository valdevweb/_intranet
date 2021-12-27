<?php

class CasseDao{

	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function insertCasse($dateCasse, $operateur, $nbColis,$categorie,$article, $dossier, $gt,$libelle, $pcb,$uvc,$valo, $panf,$fournisseur, $origine, $type, $idPalette, $etat, $detruit, $mtMag,$decote, $pfnp, $deee,$sacem,$deeeCodif, $ppi){

		$req=$this->pdo->prepare("INSERT INTO casses (date_casse, id_web_user, id_operateur, nb_colis, id_categorie, article, dossier, gt, designation, pcb, uvc,valo,pu, fournisseur, id_origine, id_type, id_palette, etat, last_maj, detruit, mt_mag, mt_decote,pfnp, deee,sacem, deee_codif, ppi) VALUES (:date_casse, :id_web_user, :id_operateur, :nb_colis, :id_categorie, :article, :dossier, :gt, :designation, :pcb, :uvc, :valo, :pu, :fournisseur, :id_origine, :id_type, :id_palette, :etat, :last_maj, :detruit, :mt_mag, :mt_decote, :pfnp, :deee,:sacem, :deee_codif, :ppi)" );

		$req->execute(array(
			':date_casse'	=>$dateCasse,
			':id_web_user'	=>$_SESSION['id_web_user'],
			':id_operateur'	=>$operateur,
			':nb_colis'	=>$nbColis,
			':id_categorie'	=>$categorie,
			':article'	=>$article,
			':dossier'	=>$dossier,
			':gt'	=>$gt,
			':designation'	=>$libelle,
			':pcb'	=>$pcb,
			':uvc'	=>$uvc,
			':valo'	=>$valo,
			':pu'	=>$panf,
			':fournisseur'	=>$fournisseur,
			':id_origine'	=>$origine,
			':id_type'	=>$type,
			':id_palette'	=>$idPalette,
			':etat'	=>$etat,
			':last_maj' =>date('Y-m-d H:i:s'),
			':detruit'	=>$detruit,
			':mt_mag'		=> $mtMag,
			':mt_decote'	=>$decote,
			':pfnp'			=>$pfnp,
			':deee'			=>$deee,
			':sacem'		=>$sacem,
			':deee_codif'	=>$deeeCodif,
			':ppi'			=>$ppi

		));
		if($req->rowCount()==1)
		{
			return $this->pdo->lastInsertId();
		}
		else{
			return false;
		}
	}

	public function copyCasse($id){
		$req=$this->pdo->prepare("INSERT INTO casses_deleted (date_casse, id_web_user, id_operateur, nb_colis, id_categorie, article, dossier, gt, designation, pcb, uvc, valo, pu, fournisseur, id_origine, id_type, id_palette, mt_mag, mt_decote, mt_ndd, num_ndd, etat, detruit, cmt, date_clos, last_maj) SELECT date_casse, id_web_user, id_operateur, nb_colis, id_categorie, article, dossier, gt, designation, pcb, uvc, valo, pu, fournisseur, id_origine, id_type, id_palette, mt_mag, mt_decote, mt_ndd, num_ndd, etat, detruit, cmt, date_clos, last_maj FROM casses WHERE id= :id");
		$req->execute([
			':id'	=>$id
		]);
		return $req->rowCount();
	}
	public function deleteCasse($id){
		$req=$this->pdo->prepare("DELETE FROM casses WHERE id= :id");
		$req->execute([
			':id'	=>$id
		]);
		return $req->rowCount();
	}

	public function getCasseByPalette($idPalette){

		$req=$this->pdo->prepare("SELECT * FROM casses WHERE id_palette= :id_palette");
		$req->execute([
			':id_palette'	=>$idPalette

		]);
		return $req->fetchAll();
	}


	public function addSerials($idCasse,$serial){
	$req=$this->pdo->prepare("INSERT INTO serials (id_casse,serial_nb) VALUES (:id_casse,:serial_nb)");
	$req->execute([
		':id_casse'			=>$idCasse,
		':serial_nb'		=>$serial
	]);
	return $req->rowCount();
}

}

