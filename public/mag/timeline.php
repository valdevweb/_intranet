<?php
require '../../config/autoload.php';
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}
include('../view/_head.php');
include('../view/_navbar.php');
include '../../functions/form.fn.php';
include "../../functions/stats.fn.php";
?>
<div class="container">
<div class="timeline-container">
  <div class="timeline left">
    <div class="inside">
      <h2>2017</h2>
      <p>Lorem ipsum..</p>
    </div>
  </div>
  <div class="timeline right">
    <div class="inside">
      <h2>2016</h2>
      <p>Lorem ipsum..</p>
    </div>
  </div>
</div>
</div>
<?php


include('../view/_footer.php');
 ?>

</body>
</html>







