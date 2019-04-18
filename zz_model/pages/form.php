<?php

include('../config/autoload.php');
//----------------------------------------------
// css dynamique
//----------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile="../css/".$page.".css";

include('../view/_head.php');
include('../view/_footer.php');




?>
<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post" >
	<button type="submit" id="submit" class="btn btn-primary" name="submit"><i class="fas fa-save pr-3"></i>Enregistrer</button>

</form>

<?php

// SELECT IF SELECTED

foreach ($gts as $gt)
{

	if($gt['id']==$fLitige['id_gt'])
	{
		$selected='selected';
	}
	else
	{
		$selected='';
	}
	echo '<option value="'..'" '.$selected.'>'..'</option>';

}

if (filter_var($email_a, FILTER_VALIDATE_EMAIL)) {
    echo "L'adresse email '$email_a' est considérée comme valide.";
}

foreach($equipes as $equipe)
{
	echo '<option value="'.$equipe['id'].'"';
	if(isset($fLitige['id_ctrl_stock']) && $fLitige['id_ctrl_stock']==$equipe['id'])
	{
		echo ' selected';
	}
	echo '>'.$equipe['name'].'</option>';
}

<!--
refresh after submit -->

		$loc='Location:'.htmlspecialchars($_SERVER['PHP_SELF']).'?success=ok';
		header($loc);
?>
<!--
MEMO

form-group + form-control
input
select option
text-area

form-check + form-check-input
input checkbox
input radio


 -->





<!-- date -->
<div class="row">
	<div class="col">
		<div class="form-group">
			<?php
			if(isset($fLitige['date_prepa']))
			{
				$datePrepa=date('Y-m-d',strtotime($fLitige['date_prepa']));
			}
			else
			{
				$datePrepa="";
			}
			?>
			<label>Date de la prépa</label>
			<input type="date" name="date_prepa" class="form-control" value="<?=$datePrepa?>">

		</div>
	</div>



	<form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" >

		<!-- INPUT TEXT  EMAIL -->
		<div class="form-group">
			<label for="mail">Email address</label>
			<input type="email" class="form-control" id="mail" name="mail" placeholder="name@example.com">
		</div>
		<!-- SELECT -->
		<div class="form-group">
			<label for="select"></label>
			<select class="form-control" id="select" name="select">
				<option>1</option>
			</select>
		</div>
		<!-- SELECT MULTIPLE -->
		<div class="form-group">
			<label for="exampleFormControlSelect2">Example multiple select</label>
			<select multiple class="form-control" id="exampleFormControlSelect2">
				<option>1</option>
				<option>2</option>
				<option>3</option>
				<option>4</option>
				<option>5</option>
			</select>
		</div>
		<!-- TEXTAREA -->
		<div class="form-group">
			<label for="text">Example textarea</label>
			<textarea class="form-control" id="text" name="text" rows="3"></textarea>
		</div>
		<!-- CHECKBOX -->
		<div class="form-check">
			<input class="form-check-input" type="checkbox" value="" id="box1" name="box1">
			<label class="form-check-label" for="defaultCheck1">Default checkbox</label>
		</div>
		<div class="form-check">
			<input class="form-check-input" type="checkbox" value="" id="box2">
			<label class="form-check-label" for="defaultCheck2">Disabled checkbox</label>
		</div>
		<!-- RADIO -->
		<div class="form-check">
			<input class="form-check-input" type="radio" name="radio" id="radio1" value="option1" checked>
			<label class="form-check-label" for="radio1">Default radio</label>
		</div>
		<div class="form-check">
			<input class="form-check-input" type="radio" name="radio" id="radio2" value="option2">
			<label class="form-check-label" for="radio2">Second default radio</label>
		</div>
		<div class="form-check">
			<input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios3" value="option3" disabled>
			<label class="form-check-label" for="exampleRadios3">
				Disabled radio
			</label>
		</div>

		<div class="form-check form-check-inline">
			<input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
			<label class="form-check-label" for="inlineCheckbox1">1</label>
		</div>
		<div class="form-check form-check-inline">
			<input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2">
			<label class="form-check-label" for="inlineCheckbox2">2</label>
		</div>
		<div class="form-check form-check-inline">
			<input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3" disabled>
			<label class="form-check-label" for="inlineCheckbox3">3 (disabled)</label>
		</div>
<!--

FORMULAIRE HORIZONTALE

-->
<form>
	<div class="form-row">
		<div class="col">
			<input type="text" class="form-control" placeholder="First name">
		</div>
		<div class="col">
			<input type="text" class="form-control" placeholder="Last name">
		</div>
	</div>
</form>
<!-- submit -->
<button type="submit" id="submit" class="btn btn-primary" name="submit">Envoyer</button>
<!-- formulaire avec upload de doc -->
<form method="post" enctype="multipart/form-data">
	<label for='incfile'>Ajouter une pièce jointe : </label><input type='file' class='form-control-file' id='incfile' name='incfile' >

	<!-- champ requis -->
	<textarea class="materialize-textarea" placeholder="Message" name="msg" required="require" id="msg" ></textarea>

	<script type="text/javascript">

		$('#answer').submit(function()
		{
			var box=$("input[type='checkbox']#clos");
			var boxState=box.prop("checked");
			if(boxState)
			{
				boxState="Confirmez l'envoi de la réponse et la cloture du dossier ?";
				//	return confirm(boxState);

			}
			else
			{
				boxState="Confirmez l'envoi de la réponse sans cloture du dossier ?";
				//	return confirm(boxState);

			}
			// console.log(boxState);
			return confirm(boxState);
		});


		$(document).ready(function (){
			$('form').submit(function()
			{
				$(":submit").text("Merci de patienter...")
				$("#submit").attr('disabled', true);

			});

		});





	</script>