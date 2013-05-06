<?php
/** 
 * Contient la division pour le sommaire, sujet � des variations suivant la 
 * connexion ou non d'un utilisateur, et dans l'avenir, suivant le type de cet utilisateur 
 * @todo  RAS
 */

?>
    <!-- Division pour le sommaire -->
    <div id="menuGauche">
     <div id="infosUtil">
    <?php      
      if (estConnecte() ) {
          $idUser = obtenirIdUserConnecte() ;
          $lgUser = obtenirDetailComptable($idConnexion, $idUser);
          $nom = $lgUser['nom'];
          $prenom = $lgUser['prenom'];            
    ?>
        <h2>
    <?php  
            echo $nom . " " . $prenom ;
    ?>
        </h2>
        <h3>Comptable</h3>        
    <?php
       }
    ?>  
      </div>  
<?php      
  if (estConnecte() ) {
?>
        <ul id="menuList">
           <li class="smenu">
              <a href="cAccueilComptable.php" title="Page d'accueil">Accueil</a>
           </li>
           <li class="smenu">
              <a href="cSeDeconnecter.php" title="Se d�connecter">Se d&eacute;connecter</a>
           </li>
           <li class="smenu">
              <a href="formValidFrais.php" title="Consultation des fiches de frais">Validation des fiches de frais</a>
           </li>
           <li class="smenu">
              <a href="cSuivrePaiementFicheFrais.php" title="Suivre le paiement des fiche de frais">Suivre le paiement des fiche de frais</a>
           </li>
         </ul>
        <?php
          // affichage des �ventuelles erreurs d�j� d�tect�es
          if ( nbErreurs($tabErreurs) > 0 ) {
              echo toStringErreurs($tabErreurs) ;
          }
  }
        ?>
    </div>
    