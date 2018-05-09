<?php
// ---------------------------------------------------
// SESSION & AUTOLOAD
//----------------------------------------------------
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
header('Location:'. ROOT_PATH.'/index.php');
}
//----------------------------------------------------
// REQUIRED FUNCTIONS
//----------------------------------------------------
require '../../functions/salon.fn.php';
// require 'export.php';

//----------------------------------------------------
// DATA LOGIC
//----------------------------------------------------
//
$nbMag=nbMagSalon($pdoBt);
$inscriptions=displayInscr($pdoBt);
$nbRepas=nbRepasFn($pdoBt);
$dayOne=dayOneFn($pdoBt);
$dayTwo=dayTwoFn($pdoBt);
$visiteOne=visiteOneFn($pdoBt);
$visiteTwo= visiteTwoFn($pdoBt);
$nbInscrJour=nbInscrJourFn($pdoBt);
// $nbInscrJour=nbInscrJourFn($pdoBt);



$nbInscription=count($inscriptions);
$listing="";
if($inscriptions){
	foreach ($inscriptions as $inscription)
	{
		//formatage des données


		// echo $inscription['id_galec'];
		$listing.='<tr><td>'.
		$inscription['id_galec']
		.'</td><td>'.
    $inscription['centrale']
    .'</td><td>'.
		$inscription['nom_mag']
		.'</td><td>'.
    $inscription['nom']
		.'</td><td>'.
		$inscription['prenom']
		.'</td><td>'.
		$inscription['fonction']
		.'</td><td>'.
		$inscription['date1']
		.'</td><td>'.
		$inscription['date2']
		.'</td><td>'.
		$inscription['visite']
		.'</td><td>'.
		$inscription['repas2']
		.'</td><td>'.
		$inscription['dateInscr']
		.'</td></tr>';
	}
}

	// echo "<pre>";
	// var_dump($inscriptions);
	// echo '</pre>';

//-----------------------------------------------------
//	css dynamique
//-----------------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";

// <---------------------------------------------------
// STATS - add rec
//-----------------------------------------------------
// require "../../functions/stats.fn.php";
// $descr="demande mag au service ".$gt ;
// $page=basename(__file__);
// $action="envoi d'une demande";
// addRecord($pdoStat,$page,$action, $descr);
//------------------------------------->
//----------------------------------------------------
// VIEW - HEADER
//----------------------------------------------------
require '../view/_head.php';
require '../view/_navbar.php';

require 'salon.ct.php';



//----------------------------------------------------
// VIEW - CONTENT
//----------------------------------------------------


?>

 <script src="https://cdn.zingchart.com/zingchart.min.js"></script>
  <script>
    var myData=[<?php
      foreach ($nbInscrJour as $result) {
        echo   $result['per_day'] . ',';
      }
      ?>];
    var myLabels=[<?php
      foreach ($nbInscrJour as $result) {
        echo '"'. $result['dateInscr'] .'",';
      }
      ?>];
      var test=[30,10,20];

      window.onload=function(){
        zingchart.render({
          id:"chartDiv",
          width:600,
          height:400,
          data:{
            "type":"bar",
            "title":{
              "text":"Nombre d'inscriptions par jour"
            },
            "plot": {
			    "value-box": {
    	  			"text": "%v"
    			}
  			},
            "scale-x":{
              "labels":myLabels
            },
            "series":[
            {
              "values":myData
            }
            ]
          }
        });
      };

    </script>

<?php

//----------------------------------------------------
// VIEW - FOOTER
//----------------------------------------------------
require '../view/_footer.php';

?>