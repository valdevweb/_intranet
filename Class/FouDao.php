
<?php


class FouDao{

	// pdoOcc
	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}


	public function getFouContactGroup($param){
		$req=$this->pdo->query("SELECT contacts.cnuf, contacts.*, fournisseur FROM contacts LEFT JOIN fournisseurs ON contacts.cnuf=fournisseurs.id $param ORDER BY fournisseur, contact");
		return $req->fetchAll(PDO::FETCH_GROUP);

	}
	public function getFouContact($cnuf){
		$req=$this->pdo->prepare("SELECT contacts.*, fournisseur FROM contacts LEFT JOIN fournisseurs ON contacts.cnuf=fournisseurs.id WHERE contacts.cnuf= :cnuf ORDER BY contact");
		$req->execute([
			':cnuf'		=>$cnuf
		]);
		return $req->fetchAll();

	}
}