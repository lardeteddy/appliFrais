<?php
/** 
 * Page d'accueil de l'application web AppliFrais
 * @package default
 * @todo  RAS
 */
  $repInclude = './include/';
  require($repInclude . "_init.inc.php");


  require($repInclude . "_enteteAcceuil.inc.html");
?>
  <!-- Division principale -->
  <div id="contenu">
  
		  <h1>Veuillez faire votre choix :<br><br></h1>
      	  <input type="button" value="Espace comptable" onClick="self.location.href='cSeConnecterComptable.php'">
		  <br>
		  <br>
		  <br>
		  <br>
		  <br>
		  <input type="button" value="Espace visiteur" onClick="self.location.href='cSeConnecterVisiteur.php'">
  </div>
<?php        
  require($repInclude . "_pied.inc.html");
  require($repInclude . "_fin.inc.php");
?>