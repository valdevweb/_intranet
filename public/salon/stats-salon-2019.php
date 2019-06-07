<?php

 // require('../../config/pdo_connect.php');
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
  echo "pas de variable session";
  header('Location:'. ROOT_PATH.'/index.php');
}
//      css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";

//---------------------------------------
//  ajout enreg dans stat
//---------------------------------------
require "../../functions/stats.fn.php";
$descr="stats salon 2019" ;
$page=basename(__file__);
$action="";
// addRecord($pdoStat,$page,$action, $descr,$code=null,$detail=null)
addRecord($pdoStat,$page,$action, $descr, 101);



function getParticipant($pdoBt)
{
  $req=$pdoBt->prepare("SELECT mag, salon_2019.galec, centrale, nom, prenom, fonction, DATE_FORMAT(date_saisie,'%d-%m-%Y') as datesaisie, mardi, mercredi,repas_mardi, repas_mercredi, date_passage, DATE_FORMAT(date_passage,'%H:%i') as heure, valise FROM salon_2019
    LEFT JOIN sca3 ON salon_2019.galec=sca3.galec
    LEFT JOIN salon_fonction ON salon_2019.id_fonction=salon_fonction.id
    WHERE salon_2019.galec !='' ORDER BY sca3.mag");
  $req->execute();
  return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getNbMagInscrit($pdoBt)
{
  $req=$pdoBt->prepare("SELECT DISTINCT(galec) FROM salon_2019 WHERE mask=0 ");
  $req->execute();
  return $req->fetchAll();
}

function getNbMagPresent($pdoBt)
{
  $req=$pdoBt->prepare("SELECT DISTINCT galec FROM (SELECT * FROM salon_2019 WHERE date_passage !='' AND mask=0) sousreq ");
  $req->execute();
  return $req->fetchAll();
}
function getNbPresent($pdoBt)
{
  $req=$pdoBt->prepare("SELECT count(id) as nb FROM salon_2019 WHERE date_passage !='' AND mask=0");
  $req->execute();
  return $req->fetch();
}


function getNbPart($pdoBt)
{
  $req=$pdoBt->prepare("SELECT sum(mardi) as p_mardi,sum(mercredi) as p_mercr,sum(repas_mardi) as repas_mardi,sum(repas_mercredi) as repas_mercr  FROM salon_2019");
  $req->execute();
  return $req->fetch(PDO::FETCH_ASSOC);
}

function getNbMagInscritMardi($pdoBt)
{
  $req=$pdoBt->prepare("SELECT DISTINCT(galec) FROM salon_2019 WHERE mardi=1 AND mask=0");
  $req->execute();
  return $req->fetchAll();
}

function getNbMagInscritMercredi($pdoBt)
{
  $req=$pdoBt->prepare("SELECT DISTINCT(galec) FROM salon_2019 WHERE mercredi=1 AND mask=0");
  $req->execute();
  return $req->fetchAll();
}

function getValise($pdoBt){
  $req=$pdoBt->prepare("SELECT count(id) as nb FROM salon_2019 WHERE valise=1");
  $req->execute();
  return $req->fetch();

}


function nbMagCentrale($pdoBt)
{
  $req=$pdoBt->prepare("SELECT count(galec) as nb,centrale FROM (SELECT DISTINCT salon_2019.galec, centrale FROM salon_2019 LEFT JOIN sca3 ON salon_2019.galec=sca3.galec WHERE salon_2019.galec !='' AND mask=0) sousreq GROUP BY centrale");
  $req->execute();
  return $req->fetchAll(PDO::FETCH_ASSOC);

}
function nbInscritFonction($pdoBt)
{
  $req=$pdoBt->prepare("SELECT count(salon_2019.id) as nb, fonction, short FROM salon_2019 LEFT JOIN salon_fonction ON id_fonction=salon_fonction.id GROUP BY short ORDER BY fonction");
  $req->execute();
  return $req->fetchAll(PDO::FETCH_ASSOC);

}

function getByHeure($pdoBt, $day){
  $req=$pdoBt->query("SELECT count(id) as nb, DATE_FORMAT(date_passage, '%H') as hour, date_passage FROM (SELECT * FROM salon_2019 WHERE DATE_FORMAT(date_passage, '%d')=$day) as sousreq GROUP by DATE_FORMAT(date_passage, '%H')");
  // return $req->rowCount();
  return $req->fetchAll(PDO::FETCH_ASSOC);
}
$statHeureMercredi=getByHeure($pdoBt, 5);

$statHeureMardi=getByHeure($pdoBt, 4);

$nbValise=getValise($pdoBt);

// echo "<pre>";
// print_r($statHeureMardi);
// echo '</pre>';

// echo "<pre>";
// print_r($statHeureMercredi);
// echo '</pre>';


// function arriveesMercredi($pdoBt){
//     $req=$pdoBt->query("SELECT count(id) as nb, substr(`heure_mercredi`,12,2) as hour, heure_mercredi FROM `salon` GROUP by substr(`heure_mercredi`,12,2)");
//   // return $req->rowCount();
//     return $req->fetchAll(PDO::FETCH_ASSOC);
// }



/*----------------------------------------------------

INSCRIPTIONS

-----------------------------------------------------*/

$perCentrale=nbMagCentrale($pdoBt);
$perFonction=nbInscritFonction($pdoBt);
$nbMagInscrit=count(getNbMagInscrit($pdoBt));
$nbMagPresent=count(getNbMagPresent($pdoBt));
$nbPart=count(getParticipant($pdoBt));
$nbPres=getNbPresent($pdoBt);
$nb=getNbPart($pdoBt);
$magMardi=count(getNbMagInscritMardi($pdoBt));
$magMercredi =count(getNbMagInscritMercredi($pdoBt));
$listParticipant=getParticipant($pdoBt);
$presence=['non','oui'];
$repas=['',' + <i class="fas fa-utensils"></i>'];
$class=['nothing','present', 'repas'];


// require_once '../../vendor/autoload.php';



//------------------------------------------------------
//      FONCTION
//------------------------------------------------------

//------------------------------------------------------
//      DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

//------------------------------------------------------
//      VIEW
//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>
<!--********************************
DEBUT CONTENU CONTAINER
*********************************-->
<div class="container">
  <img src="../img/salon/salon2019_5.png" class="float-right">
  <div class="row">
    <div class="col">
      <h1 class="text-main-blue pt-5 ">Salon BTLec 2019</h1>

    </div>
    <div class="col text-right"></div>

  </div>
  <div class="row">
    <div class="col-lg-1"></div>
    <div class="col">
      <?php
      include('../view/_errors.php');
      ?>
    </div>
    <div class="col-lg-1"></div>
  </div>
  <div class="row pb-3">
    <div class="col">
      <div class="text-main-blue heavy"> Récapitulatif :</div>
    </div>
  </div>

  <div class="row mb-3">

    <div class="col-xl-1"></div>
    <div class="col">
      <div class="row pb-3">
        <div class="col-5 text-un">Magasins/personnes inscrits :</div>
        <div class="col-2 text-un text-right"><?=$nbMagInscrit?>/<?= $nbPart?></div>
        <div class="col"></div>
      </div>
      <div class="row">
        <div class="col-5 text-deux">Magasins/personnes Mardi :</div>
        <div class="col-2 text-deux text-right"><?=$magMardi .'/'.$nb['p_mardi']?></div>
        <div class="col"></div>

      </div>
      <div class="row pb-3">
        <div class="col-5 text-deux pl-5">Dont :</div>
        <div class="col-2 text-deux text-right"><?=$nb['repas_mardi']?><i class="fas fa-utensils pl-2"></i></div>
        <div class="col"></div>

      </div>
      <div class="row">
        <div class="col-5 text-trois">Magasins/personnes Mercredi :</div>
        <div class="col-2 text-trois text-right"><?=$magMercredi.'/'.$nb['p_mercr']?></div>
        <div class="col"></div>

      </div>
      <div class="row">
        <div class="col-5 text-trois pl-5">Dont :</div>
        <div class="col-2 text-trois text-right"><?=$nb['repas_mercr']?><i class="fas fa-utensils pl-2"></i></div>
        <div class="col"></div>
      </div>

    </div>
    <div class="col-xl-1"></div>
  </div>
  <div class="row pb-3">
    <div class="col">
      <div class="text-main-blue heavy"> Répartition par centrale (nb de magasins):</div>
    </div>
  </div>
  <div class="row  mb-5">
    <div class="col">
      <div class="row justify-content-center">
        <?php
        foreach ($perCentrale as $centrale)
        {
          echo '<div class="col-auto text-center border '.strtolower($centrale['centrale']).'">'.$centrale['centrale'] .' : <br>'. $centrale['nb'] . '</div>';
        }
        ?>
      </div>
    </div>
  </div>
  <div class="row pb-3">
    <div class="col">
      <div class="text-main-blue heavy"> Répartition par fonction :</div>
    </div>
  </div>
  <div class="row  mb-5">
    <div class="col">
      <div class="row justify-content-center">
        <?php
        $precedent='';
        $nb='';
        $somme='';
        foreach ($perFonction as $fonction)
        {


          echo '<div class="col-auto text-center border">'.$fonction['short'] .' : <br>'. $fonction['nb'] . '</div>';


        }
        ?>
      </div>
    </div>
  </div>
  <div class="row pb-3">
    <div class="col">
      <h5 class="text-main-blue heavy"> Présence :</h5>
      <p>Nombre de magasins présents : <?= $nbMagPresent?></p>
      <p>Nombre de personnes présentes : <?= $nbPres['nb'].' ('.  $nbValise['nb']?> valises)</p>
    </div>
  </div>


  <div class="row">
    <div class="col-5">
      <p class="text-main-blue heavy">Heures d'arrivées mardi :</p>
    </div>
    <div class="col-2"></div>
    <div class="col-5">
      <p class="text-main-blue heavy">Heures d'arrivées mercredi :</p>
    </div>
  </div>



<div class="row">

  <div class="col-5">
    <table class="table table-sm table-bordered text-right">
      <thead class="thead-dark">
        <?php
        echo '<tr>';
        foreach ($statHeureMardi as $heureMa) {
          echo '<th>'.$heureMa['hour'].'h</th>';
        }
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        echo '<tr>';
        foreach ($statHeureMardi as $heureMa) {
          echo '<td>'.$heureMa['nb'].'</td>';
        }
        echo '</tr>';
        ?>
      </tbody>
    </table>
  </div>
  <div class="col-2"></div>


  <div class="col-5">
    <table class="table table-sm table-bordered text-right">
      <thead class="thead-dark">
        <?php
        echo '<tr>';
        foreach ($statHeureMercredi as $heureMe) {
          echo '<th>'.$heureMe['hour'].'h</th>';
        }
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        echo '<tr>';
        foreach ($statHeureMercredi as $heureMe) {
          echo '<td>'.$heureMe['nb'].'</td>';
        }
        echo '</tr>';
        ?>
      </tbody>
    </table>
  </div>
</div>

<div class="row">
  <div class="col">
    <p class="text-main-blue heavy">Présence des participants:</p>

  </div>
</div>
 <div class="row mb-3">
   <div class="col text-right">
    <a href="xl-generate-participant2019.php" class="btn btn-green"><i class="fas fa-file-excel pr-3"></i>Export présence participants</a>
  </div>
</div>
<div class="row">
  <div class="col">
    <table class="table table-sm" id="table-presence">
      <thead class="thead-dark">
        <tr>
          <th class="sortable" onclick="sortTable(0);">Magasin</th>
          <th class="sortable" onclick="sortTable(2);">Centrale</th>
          <th class="sortable" onclick="sortTable(3);">Nom</th>
          <th class="sortable" onclick="sortTable(4);">Prénom</th>
          <th class="sortable" onclick="sortTable(5);">Jour</th>
          <th class="sortable" onclick="sortTable(5);">Heure</th>
          <th class="sortable" onclick="sortTable(6);">Valise</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($listParticipant as $part)
        {
          $valise = ($part['valise']==1) ? '<i class="fas fa-suitcase text-trois"></i>' : '' ;
          if($part['date_passage'] !=''){
            $jour=date('w',strtotime($part['date_passage']));
            $jourStr = ($jour==2) ? 'mardi' : 'mercredi' ;
          }
          else{
            $jourStr='';
          }


          echo '<tr>';
          echo '<td class="'.strtolower($part['centrale']).'">'.$part['mag'].'</td>';
          echo '<td>'.$part['centrale'].'</td>';
          echo '<td>'.$part['nom'].'</td>';
          echo '<td>'.$part['prenom'].'</td>';
          echo '<td>'.$jourStr.'</td>';
          echo '<td>'.$part['heure'].'</td>';
          echo '<td class="text-center">'.$valise.'</td>';
          echo '</tr>';

        }

        ?>


      </tbody>
    </table>
  </div>
</div>






<div class="row pb-3">
  <div class="col">
    <div class="text-main-blue heavy"> Listing des inscriptions :</div>
  </div>
  <div class="col text-right">
    <a href="xl-generate-salon.php" class="btn btn-green"><i class="fas fa-file-excel pr-3"></i>Export</a>
  </div>
</div>
<div class="row">
  <div class="col">
    <table class="table table-sm" id="table-inscr">
      <thead class="thead-dark">
        <tr>
          <th class="sortable" onclick="sortTable(0);">Magasin</th>
          <th class="sortable" onclick="sortTable(1);">Galec</th>
          <th class="sortable" onclick="sortTable(2);">Centrale</th>
          <th class="sortable" onclick="sortTable(3);">Nom</th>
          <th class="sortable" onclick="sortTable(4);">Prénom</th>
          <th class="sortable" onclick="sortTable(5);">Fonction</th>
          <th class="sortable" onclick="sortTable(6);">Date Inscription</th>
          <th class="sortable" onclick="sortTable(7);">Mardi</th>
          <th class="sortable" onclick="sortTable(8);">Mercredi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($listParticipant as $part)
        {
          $bgMardi=$part['mardi'] +$part['repas_mardi'];
          $bgMardi=$class[$bgMardi];
          $bgMercredi=$part['mercredi'] +$part['repas_mercredi'];
          $bgMercredi=$class[$bgMercredi];
          echo '<tr>';
          echo '<td class="'.strtolower($part['centrale']).'">'.$part['mag'].'</td>';
          echo '<td>'.$part['galec'].'</td>';
          echo '<td>'.$part['centrale'].'</td>';
          echo '<td>'.$part['nom'].'</td>';
          echo '<td>'.$part['prenom'].'</td>';
          echo '<td>'.$part['fonction'].'</td>';
          echo '<td>'.$part['datesaisie'].'</td>';
          echo '<td class="'.$bgMardi.'">'.$presence[$part['mardi']].$repas[$part['repas_mardi']].'</td>';
          echo '<td class="'.$bgMercredi.'">'.$presence[$part['mercredi']].$repas[$part['repas_mercredi']].'</td>';
          echo '</tr>';

        }

        ?>


      </tbody>
    </table>
  </div>
</div>


<!-- ./container -->
</div>


<script src="../js/sortmultitable.js"></script>
<script type="text/javascript">


  function sortTable(n) {
    sort_table(document.getElementById("table-inscr"), n);
  }

  function sortTable(n) {
    sort_table(document.getElementById("table-presence"), n);
  }




</script>

<?php
require '../view/_footer-bt.php';
?>