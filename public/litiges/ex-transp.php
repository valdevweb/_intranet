<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
require '../../config/db-connect.php';


//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";
//------------------------------------------------------
//			FONCTION
//------------------------------------------------------


$errors=[];
$success=[];


function getTransp($pdoLitige){
	$req=$pdoLitige->prepare("SELECT * FROM transporteur ORDER BY transporteur");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

$trans=getTransp($pdoLitige);



function getTransit($pdoLitige){
	$req=$pdoLitige->prepare("SELECT * FROM transit ORDER BY transit");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

$transit=getTransit($pdoLitige);

function getAffrete($pdoLitige){
	$req=$pdoLitige->prepare("SELECT * FROM affrete ORDER BY affrete");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

$affrete=getAffrete($pdoLitige);

$tableHeadTransit=	'<table class="table border table-striped" id="transit"><thead class="thead-blue"><tr><th>Etat</th><th>Affreteur</th></tr></thead><tbody>';
$tableHead=	'<div class="col-3"><table class="table border table-striped" id="affrete"><thead class="thead-blue"><tr><th>Etat</th><th>Affreteur</th></tr></thead><tbody>';
$tablefoot='</tbody></table></div>';
$tablefootOne='</tbody></table>';

function add($pdoLitige, $key)
{

	$value=$key.'-form';
	$req=$pdoLitige->prepare("INSERT INTO $key SET $key=:value");
	$req->execute(array(
		':value'	=>$_POST[$value]
	));
	return $req->rowCount();
}

if(isset($_POST['transporteur']))
{
	$row=add($pdoLitige,'transporteur');
	if($row>0)
	{
		$loc='Location:'.htmlspecialchars($_SERVER['PHP_SELF']).'?success=ok';
		header($loc);
	}
}

if(isset($_POST['transit']))
{
	$row=add($pdoLitige,'transit');
	if($row>0)
	{
		$loc='Location:'.htmlspecialchars($_SERVER['PHP_SELF']).'?success=ok';
		header($loc);
	}
}

if(isset($_POST['affrete']))
{
	$row=add($pdoLitige,'affrete');
	if($row>0)
	{
		$loc='Location:'.htmlspecialchars($_SERVER['PHP_SELF']).'?success=ok';
		header($loc);
	}
}

if(isset($_GET['success']))
{
	$success[]='mise à jour effectuée';
}

//------------------------------------------------------
//			VIEW
//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>
<!--********************************
DEBUT CONTENU CONTAINER
*********************************-->
<div class="container">
	<h1 class="text-main-blue py-5 text-center ">Transport - exploitation</h1>
	<div class="row no-gutters">
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>
	<div class="row">
		<div class="col">
			<h3 class="text-main-blue">Transporteurs</h3>
			<div class="row">
				<div class="col">
					<p>Pour masquer ou afficher les lignes des tableaux suivant leur état, vous pouvez cliquer sur le bouton "filtrer" ci dessous</p>
					<p class="text-center" ><button class="btn btn-yellow" id="hide-transp"> <i class="fas fa-filter pr-3"></i>filtrer le tableau</button></p>

				</div>
			</div>
			<div class="row">
				<div class="col">
					<table class="table border table-striped" id="transp">
						<thead class="thead-blue">
							<tr><th>Etat</th><th>Transporteur</th></tr>
						</thead>
						<tbody>

					<?php
					foreach ($trans as $t)
					{
						if($t['mask']==0)
						{
							$ico='<a href="data-hide.php?table=transporteur&id='.$t['id'].'" class="blue-link"><i class="fas fa-eye"></i></a>';
						}
						else
						{
							$ico='<a href="data-show.php?table=transporteur&id='.$t['id'].'" class="text-dark-grey"><i class="fas fa-eye-slash"></i></a>';

						}


						echo '<tr>';
						echo'<td>'.$ico.'</td>';
						echo'<td>'.$t['transporteur'].'</td>';
						echo '</tr>';

					}
					echo $tablefootOne;
					?>
				</div>
				<div class="col-1"></div>
				<div class="col-7 mt-5">
					<p class="text-blue heavy bigger">Ajouter un transporteur : </p>
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" class="border p-3">
						<div class="form-group">
							<label>transporteur : </label>
							<input type="text" class="form-control" name="transporteur-form" required></input>
						</div>
						<div class="pt-4 mt-2 text-right">
							<button type="submit" id="submit" class="btn btn-primary" name="transporteur"><i class="fas fa-save pr-3"></i>Enregistrer</button>
						</div>
					</form>
				</div>
				<!-- <div class="col"></div> -->

			</div>
		</div>
	</div>
	<!-- affreteur -->
	<div class="row">
		<div class="col">
			<h3 class="text-main-blue mt-5">Affeteurs</h3>
			<div class="row">
				<div class="col">
					<p class="text-center" ><button class="btn btn-yellow" id="hide-affrete"> <i class="fas fa-filter pr-3"></i>filtrer le tableau</button></p>
				</div>
			</div>

			<div class="row">
				<div class="col-3">
					<table class="table border table-striped" id="transp">
						<thead class="thead-blue">
							<tr><th>Etat</th><th>Affreteur</th></tr>
						</thead>
						<tbody>
					<?php
					$nbResult=count($affrete);
					$nbLig=ceil($nbResult / 4);
					$currentLine=0;
					$nbLigMax=$nbLig;
					foreach ($affrete as $a)
					{
						if($a['mask']==0)
						{
							$ico='<a href="data-hide.php?table=affrete&id='.$a['id'].'" class="blue-link"><i class="fas fa-eye"></i></a>';
						}
						else
						{
							$ico='<a href="data-show.php?table=affrete&id='.$a['id'].'" class="text-dark-grey"><i class="fas fa-eye-slash"></i></a>';

						}
						if($currentLine<$nbLigMax)
						{
							echo '<tr>';
							echo'<td>'.$ico.'</td>';
							echo'<td>'.$a['affrete'].'</td>';
							echo '</tr>';
							$currentLine++;
						}
						else
						{
							echo $tablefoot .$tableHead;
							echo '<tr>';
							echo'<td class="test">'.$ico.'</td>';
							echo'<td>'.$a['affrete'].'</td>';
							echo '</tr>';
							$currentLine++;
							$nbLigMax=$nbLigMax +$nbLig;
						}
					}
					?>
				</tbody>
			</table>
			<!-- fin col der tableau -->
		</div>
		<!-- fin row avec les 4 tableaux -->
	</div>
	<!-- fin col contenant le row avec les 4 tableaux  -->
	<div class="row mt-3">
		<div class="col-2"></div>
		<div class="col">
			<p class="text-blue heavy bigger">Ajouter un affreteur : </p>
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" class="border p-3">
				<div class="form-group">
					<label>affreteur : </label>
					<input type="text" class="form-control" name="affrete-form" required></input>
				</div>
				<div class="pt-4 mt-2 text-right">
					<button type="submit" id="submit" class="btn btn-primary" name="affrete"><i class="fas fa-save pr-3"></i>Enregistrer</button>
				</div>
			</form>
		</div>
		<div class="col-2"></div>

	</div>

</div>
<!-- fin row affeteur -->
</div>
<div class="row">
	<div class="col">
		<h3 class="text-main-blue mt-5">Transit</h3>
		<div class="row">
			<div class="col">
				<p class="text-center" ><button class="btn btn-yellow" id="hide-transit"> <i class="fas fa-filter pr-3"></i>filtrer le tableau</button></p>
			</div>
		</div>

		<div class="row">
			<!-- <div class="col-1"></div> -->
			<div class="col">

				<?php
				echo $tableHeadTransit;
				foreach ($transit as $transi)
				{
					if($transi['mask']==0)
					{
						$ico='<a href="data-hide.php?table=transit&id='.$transi['id'].'" class="blue-link"><i class="fas fa-eye"></i></a>';
					}
					else
					{
						$ico='<a href="data-show.php?table=transit&id='.$transi['id'].'" class="text-dark-grey"><i class="fas fa-eye-slash"></i></a>';

					}


					echo '<tr>';
					echo'<td>'.$ico.'</td>';
					echo'<td>'.$transi['transit'].'</td>';
					echo '</tr>';

				}
				echo $tablefootOne;
				?>
			</div>
			<div class="col-1"></div>
			<div class="col-7 mt-5">
				<p class="text-blue heavy bigger">Ajouter un lieu de transit : </p>
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" class="border p-3">
					<div class="form-group">
						<label>Transit : </label>
						<input type="text" class="form-control" name="transit-form" required></input>
					</div>
					<div class="pt-4 mt-2 text-right">
						<button type="submit" id="submit" class="btn btn-primary" name="transit"><i class="fas fa-save pr-3"></i>Enregistrer</button>
					</div>
				</form>
			</div>


		</div>
	</div>
</div>
</div>



<!-- </div> -->

<div class="row my-5">
	<div class="col">
		<p class="text-center "><a href="exploit-ltg.php" class="btn btn-primary"><i class="fas fa-arrow-alt-circle-left pr-3"></i>Retour</a></p>
	</div>
</div>



</div>


<script type="text/javascript">
	$(document).ready(function(){
		$('#hide-transp').click(function(){
			$('#transp > tbody > tr').each(function(){
				if ($(this).find('i.fa-eye-slash').length)
				{
					$(this).toggleClass('hide');
				}
			});
		});

		$('#hide-transit').click(function(){
			$('#transit > tbody > tr').each(function(){
				if ($(this).find('i.fa-eye-slash').length)
				{
					$(this).toggleClass('hide');
				}
			});
		});
		$('#hide-affrete').click(function(){
			$('#affrete > tbody > tr').each(function(){
				if ($(this).find('i.fa-eye-slash').length)
				{
					$(this).toggleClass('hide');
				}
			});
		});



	});
</script>





<?php

require '../view/_footer-bt.php';

?>