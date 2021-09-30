<?php

class PalettesDao{

	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function getPalettesNonClos(){

		$req=$this->pdo->query("SELECT *, sum(valo) as valopalette
			FROM palettes
			LEFT JOIN casses ON palettes.id=casses.id_palette
			LEFT JOIN exps ON palettes.id_exp = exps.id
			left JOIN qlik.palettes4919 ON palettes.palette=NumeroPalette
			WHERE palettes.statut !=2 GROUP BY palettes.id ORDER BY palettes.id DESC");

		return $req->fetchAll();
	}

	public function searchWithParam($param){
		$query="SELECT *, sum(valo) as valopalette
		FROM palettes
		LEFT JOIN casses ON palettes.id=casses.id_palette
		LEFT JOIN exps ON palettes.id_exp = exps.id
		left JOIN qlik.palettes4919 ON palettes.palette=NumeroPalette
		WHERE $param GROUP BY palettes.id ORDER BY palettes.id, article DESC";


		$req=$this->pdo->query($query);
		return $req->fetchAll();


	}
	public function insertPalette($palette,$destruction){
	//palette à détruire ou non, on met en statut 0

		$req=$this->pdo->prepare("INSERT INTO palettes (palette, date_crea, statut, destruction) VALUES (:palette, :date_crea, :statut, :destruction) ");
		$req->execute([
			':palette'	=>strtoupper($palette),
			':destruction'		=>$destruction,
			':date_crea'=>date('Y-m-d H:i:s'),
			':statut'=>0
		]);
		return $this->pdo->lastInsertId();
	}
	public function getPalette($id){
		$req=$this->pdo->prepare("SELECT * FROM palettes WHERE id= :id");
		$req->execute([
			':id'	=>$id
		]);
		return $req->fetch(PDO::FETCH_ASSOC);
	}
	public function getStockPalette(){
		$req=$this->pdo->query("SELECT *, palettes.id as paletteid FROM palettes INNER JOIN qlik.palettes4919 ON palettes.palette = qlik.palettes4919.NumeroPalette GROUP BY palette ORDER BY palette");
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}



	public function updatePalette($id, $lastExp, $contremarque, $statut){
	$req=$this->pdo->prepare("UPDATE palettes SET statut= :statut, id_exp= :id_exp, contremarque= :contremarque WHERE id= :id");
	$req->execute([
		':statut'		=>$statut,
		':id_exp'		=>$lastExp,
		':id'			=>$id,
		':contremarque'	=>$contremarque

	]);
	return $req->rowCount();
}
}

