<?php


require('Form.php');

$form =new Form($_POST);

?>

<form action="" method="post">
	<?php
	echo $form->input('username');
	echo $form->submit();
		echo "<pre>";
		print_r($form);
		echo '</pre>';

	?>
</form>



<form action="" method="post">
	<?php
// $form =new Form($_POST);



// 	echo '<label for="pwd">Mot de passe : </label>';
// 	$form->tag='div';
// 	$form->string='string';


// 	echo $form->input('pwd');
// 	echo '<label for="nom">Nom : </label>';
// 	echo $form->input('nom');
// 	$form->tag='p';

// 	echo '<label for="prenom">Prénom : </label>';
// 	echo $form->input('prenom');
// 	echo $form->submit();

	?>
</form>

<div class="row">
	<div class="col"></div>
</div>