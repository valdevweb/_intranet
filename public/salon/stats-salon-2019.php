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
  $req=$pdoBt->prepare("SELECT mag, salon_2019.galec, centrale, nom, prenom, fonction, DATE_FORMAT(date_saisie,'%d-%m-%Y') as datesaisie, mardi, mercredi,repas_mardi, repas_mercredi FROM salon_2019
    LEFT JOIN sca3 ON salon_2019.galec=sca3.galec
    LEFT JOIN salon_fonction ON salon_2019.id_fonction=salon_fonction.id
    WHERE salon_2019.galec !='' ORDER BY sca3.mag");
  $req->execute();
  return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getNbMagInscrit($pdoBt)
{
  $req=$pdoBt->prepare("SELECT DISTINCT(galec) FROM salon_2019");
  $req->execute();
  return $req->fetchAll();
}

function getNbPart($pdoBt)
{
  $req=$pdoBt->prepare("SELECT sum(mardi) as p_mardi,sum(mercredi) as p_mercr,sum(repas_mardi) as repas_mardi,sum(repas_mercredi) as repas_mercr  FROM salon_2019");
  $req->execute();
  return $req->fetch(PDO::FETCH_ASSOC);
}

function getNbMagInscritMardi($pdoBt)
{
  $req=$pdoBt->prepare("SELECT DISTINCT(galec) FROM salon_2019 WHERE mardi=1");
  $req->execute();
  return $req->fetchAll();
}

function getNbMagInscritMercredi($pdoBt)
{
  $req=$pdoBt->prepare("SELECT DISTINCT(galec) FROM salon_2019 WHERE mercredi=1");
  $req->execute();
  return $req->fetchAll();
}


function nbMagCentrale($pdoBt)
{
  $req=$pdoBt->prepare("SELECT count(galec) as nb,centrale FROM (SELECT DISTINCT salon_2019.galec, centrale FROM salon_2019 LEFT JOIN sca3 ON salon_2019.galec=sca3.galec WHERE salon_2019.galec !='') sousreq GROUP BY centrale");
  $req->execute();
  return $req->fetchAll(PDO::FETCH_ASSOC);

}
function nbInscritFonction($pdoBt)
{
  $req=$pdoBt->prepare("SELECT count(salon_2019.id) as nb, fonction, short FROM salon_2019 LEFT JOIN salon_fonction ON id_fonction=salon_fonction.id GROUP BY short ORDER BY fonction");
  $req->execute();
  return $req->fetchAll(PDO::FETCH_ASSOC);

}


$perCentrale=nbMagCentrale($pdoBt);
$perFonction=nbInscritFonction($pdoBt);

$nbMagInscrit=count(getNbMagInscrit($pdoBt));
$nbPart=count(getParticipant($pdoBt));
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
      <div class="text-main-blue heavy"> Listing des inscriptions :</div>
    </div>
    <div class="col text-right">
      <a href="xl-generate-salon.php" class="btn btn-green"><i class="fas fa-file-excel pr-3"></i>Export</a>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <table class="table table-sm">
        <thead class="thead-dark">
          <tr>
            <th>Magasin</th>
            <th>Galec</th>
            <th>Centrale</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Fonction</th>
            <th>Date Inscription</th>
            <th>Mardi</th>
            <th>Mercredi</th>
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

<?php
require '../view/_footer-bt.php';
?>