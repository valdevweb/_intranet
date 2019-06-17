https://www.toutjavascript.com/savoir/savoir06_3.php3

https://www.toutjavascript.com/savoir/savoir06_4.php3

Ne permettre qu'un seul clic sur un bouton

<script type="text/javascript">
   var nbclic=0  // Initialisation à 0 du nombre de clic
   function CompteClic(formulaire) { // Fonction appelée par le bouton
      nbclic++; // nbclic+1
      if (nbclic>1) { // Plus de 1 clic
         alert("Vous avez déjà cliqué ce bouton.\nLe formulaire est en cours de traitement... Patience");
      } else {        // 1 seul clic
         alert("Premier Clic.");
      }
   }
</script>

<form name="form3">
  <input type="button" name="bouton" value="Cliquez-moi aussi !" onclick="CompteClic(this.form)">
</form>




suivant param url bloquer certains champs de formulaire en saisie (était utiisé dans bt-declaration-casse)
<script type="text/javascript">

// recup url
         // découpe pour avoir le nom du param
         // si idKs => certains champ doivent être bloqué à la modif
         var parts = window.location.href;
         parts=parts.split("?");
         parts=parts[1].split('=');

         if(parts[0]=='idKs')
         {
            $('#nb_colis').attr("disabled",true);
            $('#article').attr("disabled",true);
            $('#dossier').attr("disabled",true);
         }
</script>
