<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
    echo "pas de variable session";
    header('Location:'. ROOT_PATH.'/index.php');
}
else {
    echo "vous êtes connecté avec :";
    echo $_SESSION['id'];
}

require '../../functions/nav.fn.php';
echo "<pre>";
$rights=readRight($pdoBt,$_SESSION['id']);
$myJSON = json_encode($rights);
echo $myJSON;
echo "</pre>";




?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="<?= $tweakcss ?>">
    <link rel="stylesheet" href="<?= $md_css?>">
    <link rel="stylesheet" href="<?=$awesome ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>create nav</title>
</head>
<body>


<ul id="dropdown1" class="dropdown-content">
    <!-- <li><a href="?gt=all">toutes mes demandes</a></li> -->
    <li data-module="7"><a href="<?= $contact?>gt=brun ">achats brun</a></li>
    <li data-module="8"><a href="<?= $contact?>gt=gris ">achats gris</a></li>
    <li data-module="9"><a href="<?= $contact?>gt=pemgem ">achats PEM/GEM</a></li>
    <li data-module="16"><a href="<?= $contact?>gt=compta ">comptabilité</a></li>
    <li data-module="10"><a href="<?= $contact?>gt=communication ">communication</a></li>
    <li data-module="11"><a href="<?= $contact?>gt=direction ">direction</a></li>
    <li data-module="12"><a href="<?= $contact?>gt=dir_commerciale ">direction commerciale</a></li>
    <li data-module="13"><a href="<?= $contact?>gt=informatique ">informatique</a></li>
    <li data-module="14"><a href="<?= $contact?>gt=logistique ">logistique</a></li>
    <li data-module="15"><a href="<?= $contact?>gt=rh">social</a></li>
    <li data-module="17"><a href="<?= $contact?>gt=qualite">qualité</a></li>

</ul>
<!-- dropdown profil -->
<ul id="dropdown2" class="dropdown-content">
    <li><a href="<?= ROOT_PATH ?>/public/mag/histo.php">Vos demandes</a></li>
    <li><a href="<?= ROOT_PATH?>/public/">Votre profil</a></li>
</ul>

<ul id="dropdown3" class="dropdown-content">
    <!-- <li><a href="?gt=all">toutes mes demandes</a></li> -->
    <li data-module="18"><a href="<?= $request?>gt=brun ">achats brun</a></li>
    <li data-module="19"><a href="<?= $request?>gt=gris ">achats gris</a></li>
    <li data-module="20"><a href="<?= $request?>gt=pemgem ">achats PEM/GEM</a></li>
    <li data-module="27"><a href="<?= $request?>gt=compta ">comptabilité</a></li>
    <li data-module="21"><a href="<?= $request?>gt=communication ">communication</a></li>
    <li data-module="22"><a href="<?= $request?>gt=direction ">direction</a></li>
    <li data-module="23"><a href="<?= $request?>gt=dir_commerciale ">direction commerciale</a></li>
    <li data-module="24"><a href="<?= $request?>gt=informatique ">informatique</a></li>
    <li data-module="25"><a href="<?= $request?>gt=logistique ">logistique</a></li>
    <li data-module="26"><a href="<?= $request?>gt=rh">social</a></li>
    <li data-module="30"><a href="<?= $request?>gt=qualite">qualité</a></li>

</ul>



<!-- BARRE DE NAVIGATION -->
<nav class="light-blue darken-4" role="navigation">
    <div class="nav-wrapper">
        <!-- LOGO BT -->
        <a href="<?= ROOT_PATH?>/public/home.php "  class="brand-logo"><img src="<?=$img ?>logo-bt-mini.jpg"</a>
        <!-- btn hamburger -->
        <a href="#" data-activates="mobile-demo" class="button-collapse">
            <i class="fa fa-bars" aria-hidden="true"></i>
        </a>
        <!-- RUBRIQUES -->
        <ul class="right hide-on-med-and-down">
            <!-- home ico -->
            <li><a href="<?= ROOT_PATH ?>/public/home.php" class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="Accueil"><i class="fa fa-home fa-2x" aria-hidden="true"></i></a></li>

            <li class="mag" data-module="1" ><a class="dropdown-button" href="#"  data-activates="dropdown1" >Contacter nos services</a></li>
            <!-- demandes mag -->
              <li class="bt" data-module="2" ><a class="dropdown-button" href="#" data-activates="dropdown3" >Demandes magasin</a></li>
            <!-- entrepot -->
            <li><a href="#" class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="Visitez l'entrepôt">L'entrepôt</a></li>
            <!-- gazette -->
            <li><a href="<?= ROOT_PATH. '/public/gazette/gazette.php'?>" >La gazette</a></li>
            <!-- profil -->
            <li><a class="dropdown-button tooltipped" href="profil.php"  data-position="bottom" data-delay="50" data-tooltip="profil/demandes" data-activates="dropdown2"><i class="fa fa-user"></i></a></li>
            <!-- class="tooltipped"  -->
            <!-- logout -->
            <li><a href="<?= ROOT_PATH ?>/public/logoff.php" class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="déconnexion"><i class="fa fa-power-off"></i></a></li>
        </ul>
        <!-- VERSION TABLETTE SMARTPHONE -->
        <ul class="side-nav" id="mobile-demo">
            <li><a href="">Contacter nos services</a></li>
            <li><a href="">L'entrepôt</a></li>
            <li><a href="">La gazette</a></li>
            <li><a href="<?= $request.'gt='.$gt ?>">Demandes magasin</a></li>
            <li><a href="profil.php"><i class="fa fa-user"></i></a></li>
            <li><a href="<?= ROOT_PATH ?>/public/logoff.php"><i class="fa fa-power-off"></i></a></li>
        </ul>
    </div>
</nav>




  <footer class="monfooter light-blue darken-4">
    <div class="container">
      <div class="row">
        <div class="col l4 s12">
          <h5 class="white-text">BTLEC EST</h5>
          <p>2 rue des Moissons - Parc d'activité Witry Caurel<br>
            51420 Witry les Reims
          </p>


        </div>
        <div class="col l4 s12">
          <h5 class="white-text">Nous contacter</h5>
            <p><i class="fa fa-phone" aria-hidden="true"></i>&nbsp; &nbsp;&nbsp; 03 26 89 86 88<br></p>
              <p> <img src="/_btlecest/public/img/eleclercblue.jpg"></p>

        </div>
        <div class="col l4 s12">
          <h5 class="white-text">Venir à BT Lec</h5>
          <ul>
            <li><a class="white-text" href="#!">lien mag /infos venir </a></li>

          </ul>
        </div>
      </div>
    </div>

  </footer>
<!--  Scripts-->
<script src="<?=$jquery ?>"></script>
<script src="<?= $md_js ?>"></script>



<script src="<?= $main_js ?>"></script>
</body>
</html>