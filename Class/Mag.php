<?php
class Mag{

    private $id;
    private $deno;
    private $galec;
    private $centrale;
    private $ad1;
    private $ad2;
    private $cp;
    private $ville;
    private $tel;
    private $fax;
    private $surface;
    private $adherent;
    private $directeur;
    private $pole_sav_gessica;
    private $pole_sav;
    private $antenne;
    private $closed;
    private $absent;
    private $id_cm_intern;
    private $id_cm_web_user;
    private $btlec_sca;
    private $galec_sca;
    private $deno_sca;
    private $centrale_sca;
    private $ad1_sca;
    private $ad2_sca;
    private $ad3;
    private $cp_sca;
    private $ville_sca;
    private $tel_sca;
    private $fax_sca;
    private $surface_sca;
    private $adherent_sca;
    private $nom_gesap;
    private $lotus_rbt;
    private $obs;
    private $galec_old;
    private $centrale_doris;
    private $sorti;
    private $date_sortie;
    private $raison_sociale;
    private $mandat;
    private $date_resiliation;
    private $date_adhesion;
    private $affilie;
    private $date_fermeture;
    private $date_ouverture;
    private $docubase_login;
    private $docubase_pwd;
    private $apple_id;
    private $mots_cles;
    private $pole_sav_sca;
    private $centrale_smiley;
    private $racine_list;
    private $centreRei;
    private $rei;
    private $siret;
    private $tva;
    private $ean;
    private $gel;
    private $date_ouv;
    private $date_ferm;
    private $id_type;
    private $acdlec_pano;
    private $acdlec_activite;
    private $acdlec_code;
    private $acdlec_numord;
    private $acdlec;
    private $pole_sav_ctbt;
    private $reservable;
    private $backoffice;


    public function __construct(array $data){
        $this->hydrate($data);
    }

    public function hydrate($data){
    	foreach($data as $key => $value){
    		$underscore=explode('_',$key);
    		if(count($underscore)>1){
    			$method='';
    			for ($i=0; $i < count($underscore) ; $i++) {
    				$method.=ucfirst($underscore[$i]);
    			}
    			$method='set'.$method;
    		}
    		else{
    			$method='set'.ucfirst($key);
    		}
    		if(method_exists($this,$method)){
    			$this->$method($value);
    		}
    	}

    }

    public function getSurfaceStrg(){
        $surface=number_format((float)$this->surface,0,'',' ');
        return $surface . ' m&#xB2;' ;
    }


    public function getAffilieStr(){
        if($this->affilie==1){
            return "affilé";
        }elseif($this->affilie==0){
            return "adhérent";
        }
        return $this->affilie;
    }

    public function getDateOuvertureFr(){
       if(!empty($this->date_ouverture)){
        $dateOuv=new DateTime($this->date_ouverture);
        return $dateOuv->format('d/m/Y');
    }
    return $this->date_ouverture;

}

public function getDateOuvFr(){
   if(!empty($this->date_ouv)){
    $dateOuv=new DateTime($this->date_ouv);
    return $dateOuv->format('d/m/Y');
}
return $this->date_ouv;

}


public function getDateFermFr(){
   if(!empty($this->date_ferm)){
    $dateFerm=new DateTime($this->date_ferm);
    return $dateFerm->format('d/m/Y');
}
return $this->date_ferm;

}

public function getDateFermetureFr(){
    if(!empty($this->date_fermeture)){
        return (new DateTime($this->date_fermeture))->format('d/m/Y');
    }
    return $this->date_fermeture;
}

public function getDateSortieFr(){
    if(!empty($this->date_sortie)){
        return (new DateTime($this->date_sortie))->format('d/m/Y');
    }
    return $this->date_sortie;
}

public function getDateAdhesionFr(){
    if(!empty($this->date_adhesion)){
        return (new DateTime($this->date_adhesion))->format('d/m/Y');
    }
    return $this->date_adhesion;
}

public function getDateResiliationFr(){
    if(!empty($this->date_resiliation)){
        return (new DateTime($this->date_resiliation))->format('d/m/Y');
    }
    return $this->date_resiliation;
}


public function getGelStr(){
    if (empty($this->gel)) {
        return "nc";
    }
    $arGel=[
        0   =>"en activité",
        1   =>"en phase d'ouverture",
        9 =>"fermé",
        99 =>"nc",
    ];

    return $arGel[$this->gel];

}

public function getAcdlec(){
    $cactivite = str_pad($this->acdlec_activite, 6, '0', STR_PAD_LEFT);
    $nordre = str_pad($this->acdlec_numord, 5, '0', STR_PAD_LEFT);
    return $this->acdlec_pano.'-'.$cactivite.'-'.$this->acdlec_code.'-'.$nordre;
}


public function setAcdlec($acdlec_activite, $acdlec_code,$acdlec_numord){
    $this->acdlec=$acdlec_pano.'-'.$acdlec_activite.'-'.$acdlec_code.'-'.$acdlec_numord;
}

public function getReservableStr(){
    if($this->reservable==0){
        return "non";
    }elseif($this->reservable==1){
        return "oui";
    }
    return "";
}

public function getBackofficeStr(){
    $bo=[
        2   =>"betti",
        3   =>"aladin",
        9   =>"abaco"
    ];
    if(isset($bo[$this->backoffice])){
        return $bo[$this->backoffice];
    }
    return "non précisé";
}


public function getId(){
    return $this->id;
}

public function setId($id){
    $this->id = $id;
    return $this;
}

public function getDeno(){
    return $this->deno;
}

public function setDeno($deno){
    $this->deno = $deno;
    return $this;
}

public function getGalec(){
    return $this->galec;
}

public function setGalec($galec){
    $this->galec = $galec;
    return $this;
}

public function getCentrale(){
    return $this->centrale;
}

public function setCentrale($centrale){
    $this->centrale = $centrale;
    return $this;
}

public function getAd1(){
    return $this->ad1;
}

public function setAd1($ad1){
    $this->ad1 = $ad1;
    return $this;
}

public function getAd2(){
    return $this->ad2;
}

public function setAd2($ad2){
    $this->ad2 = $ad2;
    return $this;
}

public function getCp(){
    return $this->cp;
}

public function setCp($cp){
    $this->cp = $cp;
    return $this;
}

public function getVille(){
    return $this->ville;
}

public function setVille($ville){
    $this->ville = $ville;
    return $this;
}

public function getTel(){
    return $this->tel;
}

public function setTel($tel){
    $this->tel = $tel;
    return $this;
}

public function getFax(){
    return $this->fax;
}

public function setFax($fax){
    $this->fax = $fax;
    return $this;
}

public function getSurface(){
    return $this->surface;
}

public function setSurface($surface){
    $this->surface = $surface;
    return $this;
}

public function getAdherent(){
    return $this->adherent;
}

public function setAdherent($adherent){
    $this->adherent = $adherent;
    return $this;
}

public function getDirecteur(){
    return $this->directeur;
}

public function setDirecteur($directeur){
    $this->directeur = $directeur;
    return $this;
}

public function getPoleSavGessica(){
    return $this->pole_sav_gessica;
}

public function setPoleSavGessica($pole_sav_gessica){
    $this->pole_sav_gessica = $pole_sav_gessica;
    return $this;
}

public function getPoleSav(){
    return $this->pole_sav;
}

public function setPoleSav($pole_sav){
    $this->pole_sav = $pole_sav;
    return $this;
}

public function getAntenne(){
    return $this->antenne;
}

public function setAntenne($antenne){
    $this->antenne = $antenne;
    return $this;
}

public function getClosed(){
    return $this->closed;
}

public function setClosed($closed){
    $this->closed = $closed;
    return $this;
}

public function getAbsent(){
    return $this->absent;
}

public function setAbsent($absent){
    $this->absent = $absent;
    return $this;
}

public function getIdCmIntern(){
    return $this->id_cm_intern;
}

public function setIdCmIntern($id_cm_intern){
    $this->id_cm_intern = $id_cm_intern;
    return $this;
}

public function getIdCmWebUser(){
    return $this->id_cm_web_user;
}

public function setIdCmWebUser($id_cm_web_user){
    $this->id_cm_web_user = $id_cm_web_user;
    return $this;
}

public function getBtlecSca(){
    return $this->btlec_sca;
}

public function setBtlecSca($btlec_sca){
    $this->btlec_sca = $btlec_sca;
    return $this;
}

public function getGalecSca(){
    return $this->galec_sca;
}

public function setGalecSca($galec_sca){
    $this->galec_sca = $galec_sca;
    return $this;
}

public function getDenoSca(){
    return $this->deno_sca;
}

public function setDenoSca($deno_sca){
    $this->deno_sca = $deno_sca;
    return $this;
}

public function getCentraleSca(){
    return $this->centrale_sca;
}

public function setCentraleSca($centrale_sca){
    $this->centrale_sca = $centrale_sca;
    return $this;
}

public function getAd1Sca(){
    return $this->ad1_sca;
}

public function setAd1Sca($ad1_sca){
    $this->ad1_sca = $ad1_sca;
    return $this;
}

public function getAd2Sca(){
    return $this->ad2_sca;
}

public function setAd2Sca($ad2_sca){
    $this->ad2_sca = $ad2_sca;
    return $this;
}

public function getAd3(){
    return $this->ad3;
}

public function setAd3($ad3){
    $this->ad3 = $ad3;
    return $this;
}

public function getCpSca(){
    return $this->cp_sca;
}

public function setCpSca($cp_sca){
    $this->cp_sca = $cp_sca;
    return $this;
}

public function getVilleSca(){
    return $this->ville_sca;
}

public function setVilleSca($ville_sca){
    $this->ville_sca = $ville_sca;
    return $this;
}

public function getTelSca(){
    return $this->tel_sca;
}

public function setTelSca($tel_sca){
    $this->tel_sca = $tel_sca;
    return $this;
}

public function getFaxSca(){
    return $this->fax_sca;
}

public function setFaxSca($fax_sca){
    $this->fax_sca = $fax_sca;
    return $this;
}

public function getSurfaceSca(){
    return $this->surface_sca;
}

public function setSurfaceSca($surface_sca){
    $this->surface_sca = $surface_sca;
    return $this;
}

public function getAdherentSca(){
    return $this->adherent_sca;
}

public function setAdherentSca($adherent_sca){
    $this->adherent_sca = $adherent_sca;
    return $this;
}

public function getNomGesap(){
    return $this->nom_gesap;
}

public function setNomGesap($nom_gesap){
    $this->nom_gesap = $nom_gesap;
    return $this;
}

public function getLotusRbt(){
    return $this->lotus_rbt;
}

public function setLotusRbt($lotus_rbt){
    $this->lotus_rbt = $lotus_rbt;
    return $this;
}

public function getObs(){
    return $this->obs;
}

public function setObs($obs){
    $this->obs = $obs;
    return $this;
}

public function getGalecOld(){
    return $this->galec_old;
}

public function setGalecOld($galec_old){
    $this->galec_old = $galec_old;
    return $this;
}

public function getCentraleDoris(){
    return $this->centrale_doris;
}

public function setCentraleDoris($centrale_doris){
    $this->centrale_doris = $centrale_doris;
    return $this;
}

public function getSorti(){
    return $this->sorti;
}

public function setSorti($sorti){
    $this->sorti = $sorti;
    return $this;
}

public function getDateSortie(){
    return $this->date_sortie;
}

public function setDateSortie($date_sortie){
    $this->date_sortie = $date_sortie;
    return $this;
}

public function getRaisonSociale(){
    return $this->raison_sociale;
}

public function setRaisonSociale($raison_sociale){
    $this->raison_sociale = $raison_sociale;
    return $this;
}

public function getMandat(){
    return $this->mandat;
}

public function setMandat($mandat){
    $this->mandat = $mandat;
    return $this;
}

public function getDateResiliation(){
    return $this->date_resiliation;
}

public function setDateResiliation($date_resiliation){
    $this->date_resiliation = $date_resiliation;
    return $this;
}

public function getDateAdhesion(){
    return $this->date_adhesion;
}

public function setDateAdhesion($date_adhesion){
    $this->date_adhesion = $date_adhesion;
    return $this;
}

public function getAffilie(){
    return $this->affilie;
}

public function setAffilie($affilie){
    $this->affilie = $affilie;
    return $this;
}

public function getDateFermeture(){
    return $this->date_fermeture;
}

public function setDateFermeture($date_fermeture){
    $this->date_fermeture = $date_fermeture;
    return $this;
}

public function getDateOuverture(){
    return $this->date_ouverture;
}



public function setDateOuverture($date_ouverture){
    $this->date_ouverture = $date_ouverture;
    return $this;
}

public function getDocubaseLogin(){
    return $this->docubase_login;
}

public function setDocubaseLogin($docubase_login){
    $this->docubase_login = $docubase_login;
    return $this;
}

public function getDocubasePwd(){
    return $this->docubase_pwd;
}

public function setDocubasePwd($docubase_pwd){
    $this->docubase_pwd = $docubase_pwd;
    return $this;
}

public function getAppleId(){
    return $this->apple_id;
}

public function setAppleId($apple_id){
    $this->apple_id = $apple_id;
    return $this;
}

public function getMotsCles(){
    return $this->mots_cles;
}

public function setMotsCles($mots_cles){
    $this->mots_cles = $mots_cles;
    return $this;
}

public function getPoleSavSca(){
    return $this->pole_sav_sca;
}

public function setPoleSavSca($pole_sav_sca){
    $this->pole_sav_sca = $pole_sav_sca;
    return $this;
}

public function getCentraleSmiley(){
    return $this->centrale_smiley;
}

public function setCentraleSmiley($centrale_smiley){
    $this->centrale_smiley = $centrale_smiley;
    return $this;
}

public function getRacineList(){
    return $this->racine_list;
}

public function setRacineList($racine_list){
    $this->racine_list = $racine_list;
    return $this;
}

public function getCentreRei(){
    return $this->centreRei;
}

public function setCentreRei($centreRei){
    $this->centreRei = $centreRei;
    return $this;
}

public function getRei(){
    return $this->rei;
}

public function setRei($rei){
    $this->rei = $rei;
    return $this;
}

public function getSiret(){
    return $this->siret;
}

public function setSiret($siret){
    $this->siret = $siret;
    return $this;
}

public function getTva(){
    return $this->tva;
}

public function setTva($tva){
    $this->tva = $tva;
    return $this;
}

public function getEan(){
    return $this->ean;
}

public function setEan($ean){
    $this->ean = $ean;
    return $this;
}

public function getDateOuv(){
    return $this->date_ouv;
}

public function setDateOuv($date_ouv){
    $this->date_ouv = $date_ouv;
    return $this;
}

public function getDateFerm(){
    return $this->date_ferm;
}

public function setDateFerm($date_ferm){
    $this->date_ferm = $date_ferm;
    return $this;
}

public function getGel(){
    return $this->gel;
}

public function setGel($gel){
    $this->gel = $gel;
    return $this;
}


public function getIdType(){
    return $this->id_type;
}

public function setIdType($id_type){
    $this->id_type = $id_type;
    return $this;
}






public function getAcdlecActivite(){
    return $this->acdlec_activite;
}

public function setAcdlecActivite($acdlec_activite){
    $this->acdlec_activite = $acdlec_activite;
    return $this;
}

public function getAcdlecCode(){
    return $this->acdlec_code;
}

public function setAcdlecCode($acdlec_code){
    $this->acdlec_code = $acdlec_code;
    return $this;
}

public function getAcdlecNumord(){
    return $this->acdlec_numord;
}

public function setAcdlecNumord($acdlec_numord){
    $this->acdlec_numord = $acdlec_numord;
    return $this;
}

public function getAcdlecPano(){
    return $this->acdlec_pano;
}

public function setAcdlecPano($acdlec_pano){
    $this->acdlec_pano = $acdlec_pano;
    return $this;
}

public function getPoleSavCtbt(){
    return $this->pole_sav_ctbt;
}

public function setPoleSavCtbt($pole_sav_ctbt){
    $this->pole_sav_ctbt = $pole_sav_ctbt;
    return $this;
}

public function getReservable(){
    return $this->reservable;
}

public function setReservable($reservable){
    $this->reservable = $reservable;
    return $this;
}

public function getBackoffice(){
    return $this->backoffice;
}

public function setBackoffice($backoffice){
    $this->backoffice = $backoffice;
    return $this;
}
}
