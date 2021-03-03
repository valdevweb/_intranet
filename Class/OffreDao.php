<?php


class OffreDao{

	// pdoOcc
	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function addOffre($montant, $montantF,$pvc){
		$req=$this->pdo->prepare("INSERT INTO prospectus_offres (id_prosp, gt, marque, produit, reference, ean, pvc, offre, montant, montant_finance, by_insert, date_insert) VALUES ( :id_prosp, :gt, :marque, :produit, :reference, :ean, :pvc, :offre, :montant, :montant_finance, :by_insert, :date_insert)");
		$req->execute([
			':id_prosp'	=>$_POST['id_prosp'],
			':gt'	=>$_POST['gt'],
			':marque'	=>strtoupper(trim($_POST['marque'])),
			':produit'	=>$_POST['produit'],
			':reference'	=>$_POST['reference'],
			':ean'	=>$_POST['ean'],
			':pvc'	=>$pvc,
			':offre'	=>$_POST['offre'],
			':montant'	=>$montant,
			':montant_finance'	=>$montantF,
			':by_insert'	=>$_SESSION['id_web_user'],
			':date_insert'	=>date('Y-m-d H:i:s')
		]);
		return $req->rowCount();
	}

	public function getOffreEncours(){
		$req=$this->pdo->prepare("SELECT *, prospectus_offres.id as id_offre FROM prospectus_offres LEFT JOIN prospectus ON id_prosp= prospectus.id WHERE date_end>=:date_end ORDER BY id_prosp");
		$req->execute([
			':date_end'	=>date("Y-m-d")

		]);

		return $req->fetchAll();
	}

	public function deleteOffre($id){
		$req=$this->pdo->prepare("DELETE FROM prospectus_offres WHERE id= :id");
		$req->execute([
			':id'	=>$id
		]);
	}
		public function deleteOffreByProsp($idProsp){
		$req=$this->pdo->prepare("DELETE FROM prospectus_offres WHERE id_prosp= :id_prosp");
		$req->execute([
			':id_prosp'	=>$idProsp
		]);

	}
		public function getOffre($id){
		$req=$this->pdo->prepare("SELECT * FROM prospectus_offres WHERE id= :id");
		$req->execute([
			':id'	=>date($id)

		]);

		return $req->fetch();
	}
	public function updateOffre($id, $montant, $montantF,$pvc){
		$req=$this->pdo->prepare("UPDATE prospectus_offres SET id_prosp= :id_prosp, gt= :gt, marque= :marque, produit= :produit, reference= :reference, ean= :ean, pvc= :pvc, offre= :offre, montant= :montant, montant_finance= :montant_finance, by_insert= :by_insert, date_insert= :date_insert WHERE id= :id ");
		$req->execute([
			':id_prosp'	=>$_POST['id_prosp'],
			':gt'	=>$_POST['gt'],
			':marque'	=>strtoupper(trim($_POST['marque'])),
			':produit'	=>$_POST['produit'],
			':reference'	=>$_POST['reference'],
			':ean'	=>$_POST['ean'],
			':pvc'	=>$pvc,
			':offre'	=>$_POST['offre'],
			':montant'	=>$montant,
			':montant_finance'	=>$montantF,
			':by_insert'	=>$_SESSION['id_web_user'],
			':date_insert'	=>date('Y-m-d H:i:s'),
			':id'	=>$id,

		]);
		return $req->rowCount();
	}
}


