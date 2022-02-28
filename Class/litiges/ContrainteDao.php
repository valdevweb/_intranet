<?php
class ContrainteDao
{

    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function uniqueService()
    {
        $req = $this->pdo->query("SELECT * FROM contrainte_corresp GROUP BY service");
        return $req->fetchAll();
    }

    public function getContrainteDdeByContrainteRep($idContrainteRep)
    {
        $req = $this->pdo->prepare("SELECT id_contrainte_dde FROM contrainte_corresp WHERE id_contrainte_rep= :id_contrainte_rep");
        $req->execute([
            ':id_contrainte_rep'     => $idContrainteRep
        ]);
        return $req->fetchAll(PDO::FETCH_COLUMN);
    }

    public function findContrainte($idContrainteDde){
        $req=$this->pdo->prepare("SELECT * FROM contrainte_corresp WHERE id_contrainte_dde=:id_contrainte_dde");
        $req->execute([
            ':id_contrainte_dde'        =>$idContrainteDde
        ]);
        return $req->fetch();
    }
}
