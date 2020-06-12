
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">

    <title>Sticky Footer Template for Bootstrap</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/sticky-footer/">

    <!-- Bootstrap core CSS -->
<link rel="stylesheet" href="css/main.css ">
    <style type="text/css">
      /* Sticky footer styles
-------------------------------------------------- */
html {
  position: relative;
  min-height: 100%;
}
body {
  margin-bottom: 60px; /* Margin bottom by footer height */
}
.footer {
  position: absolute;
  bottom: 0;
  width: 100%;
  height: 60px; /* Set the fixed height of the footer here */
  line-height: 60px; /* Vertically center the text there */
  background-color: #f5f5f5;
}


/* Custom page CSS
-------------------------------------------------- */
/* Not required for template or sticky footer method. */

.container {
  width: auto;
  max-width: 680px;
  padding: 0 15px;
}


    </style>
    <!-- Custom styles for this template -->
    <link href="sticky-footer.css" rel="stylesheet">
  </head>

  <body>

<div class="row">
    <div class="col text-center">
      <img class="img-fluid sm-max px-5 px-md-1"  src="img/logos/logo.png" >

    </div>

  </div>
  <div class="row">
    <div class="col pt-3  mb-3 text-center">
      <h2 class="text-primary">Espace d'inscription</h2>
      <h6 class="sub-title">Cours de fitness Espace Club</h6>
    </div>
  </div>


  <!-- ./error/succes -->

  <div class="row mt-md-5 mb-5">
    <div class="col-md-4 mx-auto">
      <!-- error/success -->


    </div>
  </div>

  <div class="row">
    <div class="col-md-4 mx-auto">
      <div class="card justify-content-center shadow" >
        <div class="card-body">
          <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
            <div class="row px-md-5">
              <div class="col">
                <div class="form-group">
                  <label for="pseudo">Pseudo / identifiant : </label>
                  <input type="text" class="form-control" name="pseudo" id="pseudo">
                </div>
                <div class="form-group">
                  <label for="pwd">Mot de passe : </label>
                  <input type="password" class="form-control" name="pwd" id="pwd">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-auto mx-auto">
                <button class="btn btn-primary" name="submit">Se connecter</button>
              </div>
            </div>
          </form>
          <hr>
          <div class="row">
            <div class="col">
              <a href="users/registration.php" class="card-link">S'inscrire</a>
            </div>
            <div class="col-auto  text-right">
              <a href="#" class="card-link">Mot de passe oublié</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

    <footer class="footer">
      <div class="container">
        <span class="text-muted">Place sticky footer content here.</span>
      </div>
    </footer>
  </body>
</html>