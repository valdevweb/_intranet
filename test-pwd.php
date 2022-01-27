<?php 

function calcule($n1, $n2, $op){
    $result = 0;

    $n1 = floatval($n1);
    $n2 = floatval($n2);
    $op = substr($op, 0, 1);
    $_POST['operateur'] = $op;

    $result = $n1 + $n2;

	
    if($op == '*'){
        $result = $n1 * $n2;
    }
    
    if($op == '/'){
        if($n2 != 0){
            $result = $n1 / $n2;
        }else{
            $result = 'ERREUR';
        }
    }
    if($op == '-'){
        $result = $n1 - $n2;
    }
	// et modulo zero ?
    elseif($op == '%'){
        $result = $n1 % $n2;
    }else{
        $_POST['operateur'] = '+';
    }

    return($result);
}


if(isset($_POST['submit'])){
    $result = calcule($_POST['nombre1'], $_POST['nombre2'], $_POST['operateur']);
}

?>

<form action="" method="post">
	<!-- bien vu les patterns. Un petit require en plus pour éviter que l'utilisateur soumette en oubliant de renseigner un champ  -->
    <p>nombre 1 : <input type="text" name="nombre1" pattern="[0-9.,-]*"></p>
    <p>opérateur : <input type="text" name="operateur" pattern="[+/*%-]*"></p>
    <p>nombre 2 : <input type="text" name="nombre2" pattern="[0-9.,-]*"></p>
    <p><input type="submit" name="submit" value="Envoyer"></p>
</form>

<?php if(isset($_POST['submit'])) : ?>
    <?=$_POST['nombre1'].' '.$_POST['operateur'].' '.$_POST['nombre2'] ?> = <?=$result?>
<?php endif ?>