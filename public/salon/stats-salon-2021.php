<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
  header('Location:'. ROOT_PATH.'/index.php');
  exit();
}
require '../../Class/Db.php';
require '../../Class/salon/StatsSalonDao.php';
require '../../Class/MagHelpers.php';


//      css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";

$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoBt=$db->getPdo('btlec');
$pdoMag=$db->getPdo('magasin');



$statDaoThisYear=new StatsSalonDao($pdoBt,YEAR_SALON);
$statDaoLastYear=new StatsSalonDao($pdoBt,YEAR_SALON-1);
$statDaoTwoYear=new StatsSalonDao($pdoBt,YEAR_SALON-2);



$listCentrale=MagHelpers::getListCentrale($pdoMag);



$errors=[];
$success=[];


$statHeureMercredi=$statDaoThisYear->getByHeure(22);
$statHeureMardi=$statDaoThisYear->getByHeure(21);
$perCentrale=$statDaoThisYear->nbMagCentrale();
$perFonction=$statDaoThisYear->nbInscritFonction();

$nbMagInscrit=count($statDaoThisYear->getNbMagInscrit());
$nbPart=count($statDaoThisYear->getParticipantYear());
$nb=$statDaoThisYear->getNbPart();
$nbLastYear=$statDaoThisYear->getNbPart($pdoBt);
$magMardi=count($statDaoThisYear->getNbMagInscritMardi());
$magMercredi =count($statDaoThisYear->getNbMagInscritMercredi());
$listParticipant=$statDaoThisYear->getParticipantYear();
$nbMagPresent=count($statDaoThisYear->getNbMagPresent());
$nbPres=count($statDaoThisYear->getNbPresent());



$nbPresPrev=$statDaoLastYear->getNbPresent();
$nbPrev=$statDaoLastYear->getNbPart();


$perCentralePrev=$statDaoLastYear->nbMagCentrale();
$perFonctionPrev=$statDaoLastYear->nbInscritFonction();
$magMardiLastYear=count($statDaoLastYear->getNbMagInscritMardi());
$magMercrediLastYear =count($statDaoLastYear->getNbMagInscritMercredi());
$nbMagInscritPrev=count($statDaoLastYear->getNbMagInscrit());
$nbMagPresentPrev=count($statDaoLastYear->getNbMagPresent());
$nbPartPrev=count($statDaoLastYear->getParticipantYear());
$magMardiPrev=count($statDaoLastYear->getNbMagInscritMardi());
$magMercrediPrev=count($statDaoLastYear->getNbMagInscritMercredi());

$perCentraleTwo=$statDaoTwoYear->nbMagCentrale();
$perFonctionTwo=$statDaoTwoYear->nbInscritFonction();
$magMardiTwo=count($statDaoTwoYear->getNbMagInscritMardi());
$magMercrediTwo =count($statDaoTwoYear->getNbMagInscritMercredi());
$nbMagInscritTwo=count($statDaoTwoYear->getNbMagInscrit());
// $nbMagPresentTwo=count($statDaoTwoYear->getNbMagPresent());
$nbPartTwo=count($statDaoTwoYear->getParticipantYear());
$magMardiTwo=count($statDaoTwoYear->getNbMagInscritMardi());
$magMercrediTwo=count($statDaoTwoYear->getNbMagInscritMercredi());

$nbTwo=$statDaoTwoYear->getNbPart();

$presence=['non','oui'];
$repas=['',' + <i class="fas fa-utensils"></i>'];
$class=['nothing','present', 'repas'];



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
  <img src="../img/salon/salon2021-200.jpg" class="float-right">
  <div class="row">
    <div class="col">
      <h1 class="text-main-blue pt-5 ">Salon BTLec <?=YEAR_SALON?></h1>

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
  <div class="row mt-5">
    <div class="col-auto">
      <p><a href="pdf-bt-multiple.php"><button class="btn btn-primary" id="generate-bt">Générer les badges BT</button></a></p>
    </div>
    <div class="col" id="msg">

    </div>
  </div>
    <div class="row mt-5">
      <div class="col">
         <h3 class="text-main-blue">Les inscriptions magasin:</h3>
      </div>
    </div>
  <div class="row pb-3">
    <div class="col">
      <div class="text-main-blue heavy"> Récapitulatif</div>
    </div>
  </div>

  <div class="row mb-3">



    <!-- <div class="col-xl-1"></div> -->


    <div class="col">

      <table class="table table-sm">
        <thead>
          <tr>
            <th>Inscriptions</th>
            <th class="text-right"><?=YEAR_SALON?></th>
            <th class="text-right"><?=YEAR_SALON -1?></th>
            <th class="text-right"><?=YEAR_SALON -2?></th>
          </tr>
        </thead>
        <tbody>
          <tr class="text-un">
            <td>
              <b>total</b><br>
              nb mag / nb personnes :
            </td>
            <td class="text-right">
              &nbsp;<br>
              <?=$nbMagInscrit?>/<?= $nbPart?>
            </td>
            <td class="text-right">
              &nbsp;<br>
              <?=$nbMagInscritPrev?>/<?= $nbPartPrev ?>
            </td>
            <td class="text-right">
              &nbsp;<br>
              <?=$nbMagInscritTwo?>/<?= $nbPartTwo ?>
            </td>
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
              <?=$magMardiLastYear .'/'.$nbLastYear['p_mardi']?><br>
              <?=$nbLastYear['repas_mardi']?>
            </td>
            <td class="text-right">
              &nbsp;<br>
              <?=$magMardiTwo .'/'.$nbTwo['p_mardi']?><br>
              <?=$nbTwo['repas_mardi']?>
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
              <?=$magMercrediLastYear .'/'.$nbLastYear['p_mercr']?><br>
              <?=$nbLastYear['repas_mercr']?>
            </td>
            <td class="text-right">
              &nbsp;<br>
              <?=$magMercrediTwo .'/'.$nbTwo['p_mercr']?><br>
              <?=$nbTwo['repas_mercr']?>
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
        <?php foreach ($perCentrale as $centrale): ?>
         <div class="col-auto text-center border <?=strtolower($listCentrale[$centrale['centrale']])?>"><?=$listCentrale[$centrale['centrale']]?> : <br><?=$centrale['nb']?></div>
       <?php endforeach ?>

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
      <?php  foreach ($perFonction as $fonction):?>
        <div class="col-auto text-center border"><?=$fonction['short']?> : <br><?=$fonction['nb'] ?></div>
      <?php endforeach ?>
    </div>
  </div>
</div>
<?php
if($nbMagPresent!=0){
  include('stats-salon/presence.php');
}
?>
<div class="row pb-3">
  <div class="col">
    <div class="text-main-blue heavy"> Listing des inscriptions :</div>
  </div>
  <div class="col text-right">
    <a href="xl-generate-salon-2021.php" class="btn btn-green"><i class="fas fa-file-excel pr-3"></i>Export</a>
    <!-- <a href="../achats-offres/xl-offres.php" class="btn btn-green"><i class="fas fa-file-excel pr-3"></i>Export o</a> -->
  </div>
</div>


<?php if (!empty($listParticipant)): ?>
 <?php include 'stats-salon/inscriptions.php' ?>
<?php endif ?>
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