<?php
if(isset($errors)&& count($errors)!=0){

    echo '<div class="alert alert-danger">';

    foreach ($errors as $error) {
        echo $error .'<br>';
    }

    echo '</div>';
}
elseif((isset($success) && count($success) !=0) || (isset($info) && count($info) !=0))
{
    if(isset($success) && count($success) !=0)
    {
        echo '<div class="alert alert-success" role="alert">';
        foreach ($success as $s)
        {
            echo $s .'<br>';
        }
        echo '</div>';
    }
    if(isset($info) && count($info) !=0)
    {
        echo '<div class="alert alert-primary" role="alert">';
        foreach ($info as $i)
        {
            echo $i .'<br>';
        }
        echo '</div>';

    }

}