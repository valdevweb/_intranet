<?php

include('../config/autoload.php');
$_SESSION = array();
session_destroy();
header('Location : '.ROOT_PATH .'/index.php');
 ?>