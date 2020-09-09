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





function getListFournisseur($pdoBt){
  $req=$pdoBt->query("SELECT * FROM salon_fournisseurs");
  return $req->fetchAll(PDO::FETCH_ASSOC);

}

function getFournisseurParticipants($pdoBt, $idFournisseur){
  $req=$pdoBt->prepare("SELECT * FROM salon_fournisseurs_presence WHERE id_fournisseur = :id_fournisseur");
  $req->execute([
    ':id_fournisseur' =>$idFournisseur
  ]);
  return $req->fetchAll(PDO::FETCH_ASSOC);

}

$listFournisseur=getListFournisseur($pdoBt);





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

      <div class="row">
        <div class="col">
          <div class="text-main-blue heavy"> <h4>Listing des fournisseurs </h4></div>
        </div>
      </div>

      <div class="row">
        <div class="col">

          <table class="table">
            <thead class="thead-dark">
              <tr>
                <th>Nom du fournisseur</th>
                <th>Nom des personnes présentes</th>
                <th>Badges</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($listFournisseur as $key => $fournisseurMain): ?>
                <?php $fournisseurs=getFournisseurParticipants($pdoBt, $fournisseurMain['id']);?>

                <tr>
                  <td><?=$fournisseurMain['fournisseur']?></td>
                  <td>
                    <?php foreach ($fournisseurs as $key => $f): ?>
                      <?=$f['nom'] .' ' .$f['prenom']?><br>
                    <?php endforeach ?>
                  </td>
                  <td><a href="pdf-fournisseur.php?id=<?=$fournisseurMain['id']?>" class="btn btn-primary">Badge</a></td>
                </tr>
              <?php endforeach ?>

            </tbody>
          </table>

        </div>
      </div>


      <!-- ./container -->
    </div>



    <?php
    require '../view/_footer-bt.php';
    ?>