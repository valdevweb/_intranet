<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
require '../../config/db-connect.php';

//----------------------------------------------------------------
require_once '../../functions/form.fn.php';
require "../../functions/stats.fn.php";
require "../../Class/BtUserManager.php";
$descr="historique côté mag";
$page=basename(__file__);
$action="tableau général";
addRecord($pdoStat,$page,$action, $descr);
//----------------------------------------------------------------
//			css dynamique
//----------------------------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";

$userManager=new BtUserManager();
function listAllMsg($pdoBt){
	$data=array();
	$req=$pdoBt->prepare("SELECT id FROM msg WHERE id_mag= :id_mag  ORDER BY date_msg DESC");
	$req->execute(array(
		':id_mag'	=>$_SESSION['id'],

	));

	if($idExist=$req->fetchAll(PDO::FETCH_COLUMN))
		{
			foreach ($idExist as $key => $value) {

				//recup id der réponse
				// SELECT * FROM replies WHERE date_reply IN (SELECT max(date_reply) FROM replies GROUP BY id_msg)

				$req=$pdoBt->prepare("SELECT table_msg.id AS msg_id, objet, msg, id_service, date_msg, table_msg.etat, table_replies.replied_by, table_replies.reply, max(table_replies.date_reply), table_replies.id AS reply_id  FROM msg table_msg LEFT JOIN replies table_replies ON table_msg.id = table_replies.id_msg WHERE table_replies.id_msg= :idMsg AND date_reply IN (SELECT max(date_reply) FROM replies GROUP BY id_msg)");
				$req->execute(array(
					':idMsg'	=>$value

				));
				// $data=$req->fetch(PDO::FETCH_ASSOC)
				array_push($data,$req->fetch(PDO::FETCH_ASSOC));

			}
			return $data;

		}

	}

$allMsg=listAllMsg($pdoBt);

	//tri le tableau en fonction des id réponse et date msg

//header et nav bar
include ('../view/_head-bt.php');
include ('../view/_navbar.php');
// echo "session " . $_SESSION['id'];
//contenu
include('histo-mag.ct.php');


// footer avec les scripts et fin de html
include('../view/_footer-bt.php');