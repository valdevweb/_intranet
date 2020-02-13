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
$descr="stats salon 2020" ;
$page=basename(__file__);
$action="";
// addRecord($pdoStat,$page,$action, $descr,$code=null,$detail=null)
addRecord($pdoStat,$page,$action, $descr, 101);



function getParticipantYear($pdoBt, $year){
  $table="salon_".$year;

  $req=$pdoBt->prepare("SELECT mag, $table.galec, centrale, nom, prenom, fonction, DATE_FORMAT(date_saisie,'%d-%m-%Y') as datesaisie, mardi, mercredi,repas_mardi, repas_mercredi, date_passage, DATE_FORMAT(date_passage,'%H:%i') as heure FROM $table
    LEFT JOIN sca3 ON $table.galec=sca3.galec
    LEFT JOIN salon_fonction ON $table.id_fonction=salon_fonction.id
    WHERE $table.galec !='' ORDER BY sca3.mag");
    $req->execute();

 return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getNbMagInscrit($pdoBt,$year){
  $table="salon_".$year;
  $req=$pdoBt->prepare("SELECT DISTINCT(galec) FROM {$table} WHERE mask=0 ");
  $req->execute();
  return $req->fetchAll();
}

function getNbMagPresent($pdoBt,$year){
  $table="salon_".$year;
  $req=$pdoBt->prepare("SELECT DISTINCT galec FROM (SELECT * FROM {$table} WHERE date_passage !='' AND mask=0) sousreq ");
  $req->execute();
  return $req->fetchAll();
}
function getNbPresent($pdoBt, $year){
  $table="salon_".$year;
  $req=$pdoBt->prepare("SELECT count(id) as nb FROM {$table} WHERE date_passage !='' AND mask=0");
  $req->execute();
  return $req->fetch();
}


function getNbPart($pdoBt,$year){
  $table="salon_".$year;
  $req=$pdoBt->prepare("SELECT sum(mardi) as p_mardi,sum(mercredi) as p_mercr,sum(repas_mardi) as repas_mardi,sum(repas_mercredi) as repas_mercr  FROM {$table}");
  $req->execute();
  return $req->fetch(PDO::FETCH_ASSOC);
}

function getNbMagInscritMardi($pdoBt, $year){
  $table="salon_".$year;
  $req=$pdoBt->prepare("SELECT DISTINCT(galec) FROM {$table} WHERE mardi=1 AND mask=0");
  $req->execute();
  return $req->fetchAll();
}

function getNbMagInscritMercredi($pdoBt, $year){
  $table="salon_".$year;
  $req=$pdoBt->prepare("SELECT DISTINCT(galec) FROM {$table} WHERE mercredi=1 AND mask=0");
  $req->execute();
  return $req->fetchAll();
}

function getValise($pdoBt){
  $req=$pdoBt->prepare("SELECT count(id) as nb FROM salon_2020 WHERE valise=1");
  $req->execute();
  return $req->fetch();

}


function nbMagCentrale($pdoBt,$year){
  $table="salon_".$year;

  $req=$pdoBt->prepare("SELECT count(galec) as nb,centrale FROM (SELECT DISTINCT {$table}.galec, centrale FROM {$table} LEFT JOIN sca3 ON {$table}.galec=sca3.galec WHERE {$table}.galec !='' AND mask=0) sousreq GROUP BY centrale");
  $req->execute();
  return $req->fetchAll(PDO::FETCH_ASSOC);

}
function nbInscritFonction($pdoBt, $year){
  $table="salon_".$year;


  $req=$pdoBt->prepare("SELECT count($table.id) as nb, fonction, short FROM {$table} LEFT JOIN salon_fonction ON id_fonction=salon_fonction.id GROUP BY short ORDER BY fonction");
  $req->execute();
  return $req->fetchAll(PDO::FETCH_ASSOC);

}

function getByHeure($pdoBt, $day){
  $req=$pdoBt->query("SELECT count(id) as nb, DATE_FORMAT(date_passage, '%H') as hour, date_passage FROM (SELECT * FROM salon_2020 WHERE DATE_FORMAT(date_passage, '%d')=$day) as sousreq GROUP by DATE_FORMAT(date_passage, '%H')");
  // return $req->rowCount();
  return $req->fetchAll(PDO::FETCH_ASSOC);
}
$statHeureMercredi=getByHeure($pdoBt, 5);

$statHeureMardi=getByHeure($pdoBt, 4);

$nbValise=getValise($pdoBt);




/*----------------------------------------------------

INSCRIPTIONS

-----------------------------------------------------*/
$now="2020";
$prev="2019";


$perCentrale=nbMagCentrale($pdoBt,$now);

$perFonction=nbInscritFonction($pdoBt,$now);
$perCentralePrev=nbMagCentrale($pdoBt, $prev);
$perFonctionPrev=nbInscritFonction($pdoBt,$prev);



$nbMagInscrit=count(getNbMagInscrit($pdoBt,$now));
$nbMagPresent=count(getNbMagPresent($pdoBt,$now));
$nbPart=count(getParticipantYear($pdoBt,$now));

$nbPres=getNbPresent($pdoBt,$now);
$nb=getNbPart($pdoBt, $now);
$magMardi=count(getNbMagInscritMardi($pdoBt,$now));
$magMercredi =count(getNbMagInscritMercredi($pdoBt, $now));
$listParticipant=getParticipantYear($pdoBt,$now);


$presence=['non','oui'];
$repas=['',' + <i class="fas fa-utensils"></i>'];
$class=['nothing','present', 'repas'];


$nbMagInscritPrev=count(getNbMagInscrit($pdoBt,$prev));
$nbMagPresentPrev=count(getNbMagPresent($pdoBt,$prev));
$nbPartPrev=count(getParticipantYear($pdoBt,$prev));


$nbPresPrev=getNbPresent($pdoBt,$prev);
$nbPrev=getNbPart($pdoBt, $prev);
$magMardiPrev=count(getNbMagInscritMardi($pdoBt,$prev));
$magMercrediPrev=count(getNbMagInscritMercredi($pdoBt, $prev));




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
  <img src="../img/salon/salon2020-200.jpg" class="float-right">
  <div class="row">
    <div class="col">
      <h1 class="text-main-blue pt-5 ">Salon BTLec 2020</h1>

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



    <!-- <div class="col-xl-1"></div> -->


    <div class="col">

      <table class="table table-sm">
        <thead>
          <tr>
            <th>Inscriptions</th>
            <th class="text-right">2020</th>
            <th class="text-right">2019</th>
          </tr>
        </thead>
        <tbody>
          <tr class="text-un">
            <td >
              <b>total</b><br>
            nb mag / nb personnes :</td>
            <td class="text-right">
              &nbsp;<br>
              <?=$nbMagInscrit?>/<?= $nbPart?></td>
            <td class="text-right">
              &nbsp;<br>
              <?=$nbMagInscritPrev?>/<?= $nbPartPrev ?></td>
          </tr>

          <tr class="text-deux">
            <td >
              <b>Mardi : </b><br>
              nb mag / nb personnes :<br>
              repas :
            </td>
            <td class="text-right">
              &nbsp;<br>
              <?=$magMardi .'/'.$nb['p_mardi']?><br>
              <?=$nb['repas_mardi']?>
            </td>
            <td class="text-right">
              &nbsp;<br>
              <?=$magMardiPrev .'/'.$nbPrev['p_mardi']?><br>
              <?=$nbPrev['repas_mardi']?>
            </td>
          </tr>

          <tr class="text-trois">
            <td >
              <b>Mercredi : </b><br>
              nb mag / nb personnes :<br>
              repas :
            </td>
            <td class="text-right">
              &nbsp;<br>
              <?=$magMercredi .'/'.$nb['p_mercr']?><br>
              <?=$nb['repas_mercr']?>
            </td>

            <td class="text-right">
              &nbsp;<br>
              <?=$magMercrediPrev .'/'.$nbPrev['p_mercr']?><br>
              <?=$nbPrev['repas_mercr']?>
            </td>

            <td></td>
          </tr>
        </tbody>
      </table>


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

  <?php
  if(new DateTime()>=new DateTime("2020-06-09")){
    include('stats-salon-2020-presence.php');

  }

  ?>






  <div class="row pb-3">
    <div class="col">
      <div class="text-main-blue heavy"> Listing des inscriptions :</div>
    </div>
    <div class="col text-right">
      <a href="xl-generate-salon-2020.php" class="btn btn-green"><i class="fas fa-file-excel pr-3"></i>Export</a>
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