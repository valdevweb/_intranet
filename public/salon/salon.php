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
$centralesDecompte=nbVenusParCentrale($pdoBt);
$nbVenusReels=nbVenus($pdoBt);
$noScan=nbManuel($pdoBt);
$nbInscription=count($inscriptions);
$deltaInscritReel= $nbVenusReels * 100 / $nbInscription;
$deltaScan=$noScan *100 /$nbVenusReels;
$heuresMardi=arriveesMardi($pdoBt);
unset($heuresMardi[0]);
$heuresMercredi=arriveesMercredi($pdoBt);
//on retire les heures vides
unset($heuresMercredi[0]);
//on retire les scan auto de la veille
unset($heuresMercredi[1]);

  // echo "<pre>";
  // var_dump($heuresMercredi);
  // echo '</pre>';



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
    // donnees graph découpage par centrales des participants
    var nbVenus=[<?php
      foreach ($centralesDecompte as $result) {
        echo   $result['nb'] . ',';
      }
      ?>];
    var centrales=[<?php
      foreach ($centralesDecompte as $result) {
        echo '"'.strtolower($result['centrale']) .'",';
      }
      ?>];
      //graph heures scan
      var nbScanMa=[<?php
      foreach ($heuresMardi as $result) {
        echo   $result['nb'] . ',';
      }
      ?>];
    var heuresMardi=[<?php
      foreach ($heuresMardi as $result) {
        echo '"'.$result['hour'] .'",';
      }
      ?>];
      var nbScanMe=[<?php
      foreach ($heuresMercredi as $result) {
        echo   $result['nb'] . ',';
      }
      ?>];
    var heuresMercredi=[<?php
      foreach ($heuresMercredi as $result) {
        echo '"'.$result['hour'] .'",';
      }
      ?>];


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
        zingchart.render({
          id:"venuesCentrales",
          width:1000,
          height:400,
          data:{
            "type":"bar",
            "title":{
              "text":"Nombre participants par centrales"
            },
            "plot": {
          "value-box": {
              "text": "%v"
          }
        },
            "scale-x":{
                  "item":{
                    "font-size":10
                  },
                  "labels":centrales,
                },
            "series":[
            {
              "values":nbVenus
            }
            ]
          }
        });
         zingchart.render({
          id:"heuresMardi",
          width:800,
          height:400,
          data:{
            "type":"bar",
            "title":{
              "text":"Heures d'arrivée mardi"
            },
           "plot": {
                      "styles":["#ff6666","#ff6666","#ff6666","#ff6666","#ff6666"],
                      "value-box": {
                        "text": "%v"
                      }
                    },
            "scale-x":{
                  "item":{
                    "font-size":10
                  },
                  "labels":heuresMardi,
                },
            "series":[
            {
              "values":nbScanMa
            }
            ]
          }
        });
         zingchart.render({
          id:"heuresMercredi",
          width:800,
          height:400,
          data:{
            "type":"bar",
            "title":{
              "text":"Heures d'arrivée mercredi"
            },
            // "#cc99ff"
             "plot": {
                      "styles":["#cc99ff","#cc99ff","#cc99ff","#cc99ff","#cc99ff"],
                      "value-box": {
                        "text": "%v"
                      }
                    },
            "scale-x":{
                  "item":{
                    "font-size":10
                  },
                  "labels":heuresMercredi,
                },
            "series":[
            {
              "values":nbScanMe
            }
            ]
          }
        });

      };


      // var test=[30,10,20];





    </script>

<?php

//----------------------------------------------------
// VIEW - FOOTER
//----------------------------------------------------
require '../view/_footer.php';

?>