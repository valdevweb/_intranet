<?php
if(isset($errors)&& count($errors)!=0){

    echo '<div class="alert alert-danger">';

    foreach ($errors as $error) {
        echo $error .'<br>';
    }

    echo '</div>';
}

if(isset($success)&& count($success)!=0){

    echo '<div class="alert alert-success">';

    foreach ($success as $s) {
        echo $s .'<br>';
    }

    echo '</div>';
}
