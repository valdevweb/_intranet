<?php
if (isset($errors) && count($errors) != 0) {

    echo '<div class="alert alert-danger role="alert">';
    foreach ($errors as $error)
    {
        echo $error . '<br/>';
    }
    echo '</div>';

}
elseif(isset($success) && count($success) !=0)
{
	echo '<div class="alert alert-success" role="alert">';
	foreach ($success as $s) {
		echo $s .'<br>';
	}
    echo '</div>';

}

