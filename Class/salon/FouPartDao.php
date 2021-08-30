<?php

class FouPartDao{

	private $pdo;
	private $yearSalon="2021";

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}



	public function getParticipantsInscrits(){
		$req=$this->pdo->query("SELECT participants_{$this->yearSalon}.*, fournisseurs.fournisseurs.fournisseur, fournisseurs.contacts.email FROM participants_{$this->yearSalon}
			LEFT JOIN fournisseurs.fournisseurs ON participants_{$this->yearSalon}.cnuf=fournisseurs.fournisseurs.id
			LEFT JOIN fournisseurs.contacts ON participants_{$this->yearSalon}.id_contact=fournisseurs.contacts.id
			WHERE confirmed= 1 ORDER by fournisseurs.fournisseurs.fournisseur, nom ");

		return $req->fetchAll();
	}



}
?>