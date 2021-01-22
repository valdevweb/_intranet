<?php
if(isset($errors)&& !empty($errors)){

    echo '<div class="alert alert-danger">';

    foreach ($errors as $error) {
        echo $error .'<br>';
    }

    echo '</div>';
}
elseif((isset($success) && !empty($success)) || (isset($info) && !empty($info)))
{
    if(isset($success) && !empty($success))
    {
        echo '<div class="alert alert-success" role="alert">';
        foreach ($success as $s)
        {
            echo $s .'<br>';
        }
        echo '</div>';
    }
    if(isset($info) && !empty($info))
    {
        echo '<div class="alert alert-primary" role="alert">';
        foreach ($info as $i)
        {
            echo $i .'<br>';
        }
        echo '</div>';

    }

}