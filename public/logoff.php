<?php

include('../config/autoload.php');
session_destroy();
header('Location : '.ROOT_PATH .'/index.php');
 ?>