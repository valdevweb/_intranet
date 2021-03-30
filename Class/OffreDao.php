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

	public function addOffre($idProsp, $gt, $marque, $produit, $ref, $ean, $pvc, $offre, $montant, $montantF,$euro, $cmt){
		$req=$this->pdo->prepare("INSERT INTO prospectus_offres (id_prosp, gt, marque, produit, reference, ean, pvc, offre, montant, montant_finance, euro, cmt, by_insert, date_insert) VALUES ( :id_prosp, :gt, :marque, :produit, :reference, :ean, :pvc, :offre, :montant, :montant_finance, :euro, :cmt, :by_insert, :date_insert)");
		$req->execute([
			':id_prosp'	=>$idProsp,
			':gt'	=>$gt,
			':marque'	=>$marque,
			':produit'	=>$produit,
			':reference'	=>$ref,
			':ean'	=>$ean,
			':pvc'	=>$pvc,
			':offre'	=>$offre,
			':montant'	=>$montant,
			':montant_finance'	=>$montantF,
			':euro'				=>$euro,
			':cmt'				=>$cmt,
			':by_insert'	=>$_SESSION['id_web_user'],
			':date_insert'	=>date('Y-m-d H:i:s')
		]);
		return $req->rowCount();
	}

	public function getOffreEncoursByProsp(){
		$dateStart=new DateTime();
		$dateEnd=new DateTime();
		$dateStart=$dateStart->modify('+ 15 day');
		$dateEnd=$dateEnd->modify('- 7 day');
		$req=$this->pdo->prepare("SELECT prospectus_offres.id_prosp, prospectus_offres.id as id_offre, prospectus_offres.* FROM prospectus_offres LEFT JOIN prospectus ON id_prosp= prospectus.id WHERE date_start<= :date_start AND date_end>= :date_end ORDER BY id_prosp");
		$req->execute([
			':date_end'		=>$dateEnd->format("Y-m-d"),
			':date_start'	=>$dateStart->format("Y-m-d"),

		]);

		return $req->fetchAll(PDO::FETCH_GROUP);
	}
	public function getOffreEncours(){
		$today=new DateTime();
		$today->modify('- 3 day');


		$req=$this->pdo->prepare("SELECT *, prospectus_offres.id as id_offre FROM prospectus_offres LEFT JOIN prospectus ON id_prosp= prospectus.id WHERE date_end>=:date_end ORDER BY id_prosp");
		$req->execute([
			':date_end'	=>$today->format("Y-m-d")

		]);

		return $req->fetchAll();
	}
	public function deleteOffre($id){
		$req=$this->pdo->prepare("DELETE FROM prospectus_offres WHERE id= :id");
		$req->execute([
			':id'	=>$id
		]);
	}
	public function deleteLink($id){
		$req=$this->pdo->prepare("DELETE FROM prospectus_links WHERE id= :id");
		$req->execute([
			':id'	=>$id
		]);
	}
	public function deleteFile($id){
		$req=$this->pdo->prepare("DELETE FROM prospectus_files WHERE id= :id");
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
			':id'	=>$id
		]);
		return $req->fetch();
	}
	public function getOffresByIdProsp($idProsp){
		$req=$this->pdo->prepare("SELECT * FROM prospectus_offres LEFT JOIN prospectus ON prospectus_offres.id_prosp=prospectus.id WHERE id_prosp= :id_prosp");
		$req->execute([
			':id_prosp'	=>$idProsp
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

	public function updateFiles($idFile, $filename, $ordre){
		$req=$this->pdo->prepare("UPDATE prospectus_files SET filename= :filename, ordre= :ordre WHERE id= :id");
		$req->execute([
			':id'		=>$idFile,
			':filename'		=>$filename,
			':ordre'		=>$ordre,
		]);
		return $req->errorInfo();
	}
	public function updateLinks($idLink, $linkname){
		$req=$this->pdo->prepare("UPDATE prospectus_links SET linkname= :linkname WHERE id= :id");
		$req->execute([
			':id'		=>$idLink,
			':linkname'		=>$linkname,
		]);
		return $req->errorInfo();
	}


}


