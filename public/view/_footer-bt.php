<!-- footer -->


<footer class="footer">

  <div class="container-fluid dark-blue-bg">
    <div class="row">
      <div class="col">

        <h5>BTLEC EST</h5>
        <p>2 rue des Moissons - Parc d'activité Witry Caurel</p>
        <p>51420 Witry les Reims</p>
      </div>

      <div class="col">
        <h5 class="white-text">Nous contacter</h5>
        <p><i class="fa fa-phone" aria-hidden="true"></i>&nbsp; &nbsp;&nbsp; 03 26 89 86 88<br></p>
        <p><i class="fa fa-envelope-o" aria-hidden="true"></i>&nbsp; &nbsp;&nbsp;<a class="link-white" href="#">Envoyer un mail à BTlec</a>


        </div>

        <div class="col">
         <h5 class="white-text">Plus d'infos</h5>
         <p>
          <i class="fa fa-globe" aria-hidden="true"></i>&nbsp; &nbsp;<a class="link-white" href="../mag/google-map.php">Venir à BTlec</a>
        </p>
        <p class="logo-footer"> <img src="../img/footer/eleclercblue.jpg"></p>

      </div>
    </div>

  </div>
</footer>
<script type="text/javascript">
  $(document).ready(function(){

    function checkSession(){

      $.ajax({
        url:"../../config/checksession.php",
        method:"POST",
        success:function(data){
          if(data==1){
            alert("Votre session a expirée, vous allez être déconnecté");
            window.location.href='../../index.php';
          }
          else{
            // console.log(data);
          }
        }
      });
    }

    setInterval(function(){
      checkSession();
    },10000);

  });

</script>


</body>
</html>