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

	public function insertCasse($dateCasse, $operateur, $nbColis,$categorie,$article, $dossier, $gt,$libelle, $pcb,$uvc,$valo, $panf,$fournisseur, $origine, $type, $idPalette, $etat, $detruit, $mtMag,$decote, $pfnp, $deee,$sacem,$deeeCodif){

		$req=$this->pdo->prepare("INSERT INTO casses (date_casse, id_web_user, id_operateur, nb_colis, id_categorie, article, dossier, gt, designation, pcb, uvc,valo,pu, fournisseur, id_origine, id_type, id_palette, etat, last_maj, detruit, mt_mag, mt_decote,pfnp, deee,sacem, deee_codif) VALUES (:date_casse, :id_web_user, :id_operateur, :nb_colis, :id_categorie, :article, :dossier, :gt, :designation, :pcb, :uvc, :valo, :pu, :fournisseur, :id_origine, :id_type, :id_palette, :etat, :last_maj, :detruit, :mt_mag, :mt_decote, :pfnp, :deee,:sacem, :deee_codif)" );

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
			':deee_codif'	=>$deeeCodif

		));
		if($req->rowCount()==1)
		{
			return $this->pdo->lastInsertId();
		}
		else{
			return false;
		}
	}

}

