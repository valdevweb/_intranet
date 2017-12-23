<?php
require '../config/autoload.php';
$id=$_SESSION['id'];







?>
<!DOCTYPE html>
<html>
 <head>
  <title>creation de mag</title>

 </head>
 <body>
  <h1>Création de magasin</h1>
    <div class="container" style="width:600px;">
   <select name="centrale" id="centrale" class="action">
    <option value="">Centrale</option>
    <?php echo $centrale; ?>
   </select>
   <br />
   <select name="name_mag" id="name_mag" class="">
    <option value="">Magasin</option>
   </select>
   <br />
   <!-- <select name="city" id="city" class="form-control">
    <option value="">Select City</option>
   </select> -->
  </div>


<?php
include('public/view/_footer.php');

 ?>


 </body>
</html>

<script>
$(document).ready(function(){
 $('.action').change(function(){
  if($(this).val() != '')
  {
   var action = $(this).attr("id");
   var query = $(this).val();
   console.log(query);
   var result = '';
   if(action == "centrale")
   {
    result = 'name_mag';
    console.log ("action centrale");
   }
   else
   {
    console.log('je nexiste pas');
   }
   $.ajax({
    url:"list_mag.php",
    method:"POST",
    data:{action:action, query:query},
    success:function(data){
    $('#'+'name_mag').html(data);
        // $('#page_details').html(data);

    console.log(data);
    }
   })
  }
 });
});
</script>



