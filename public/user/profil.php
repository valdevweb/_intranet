<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
  echo "pas de variable session";
  header('Location:'. ROOT_PATH.'/index.php');
}
include('../view/_head.php');
include('../view/_navbar.php');

?>
<div class="container">
  <div class="row">
    <h1 class="light-blue-text text-darken-2">Mon profil</h1>
  </div>
</div>

<?php
include('../view/_footer.php');

?>