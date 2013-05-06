<?php
/**
 * Page d'accueil de l'application web AppliFrais
 * @package default
 * @todo  RAS
 */
$repInclude = './include/';
require($repInclude . "_init.inc.php");

// page inaccessible si visiteur non connecté
if (!estConnecte()) {
    header("Location: cSeConnecterVisiteur.php");
}
require($repInclude . "_entete.inc.html");
require($repInclude . "_sommaireVisiteur.inc.php");
?>
<!-- Division principale -->
<div id="contenu">
    <h2>Bienvenue sur l'intranet GSB</h2>
    <br><br><br><br>
    Bienvenue, sur votre compte GSB,
    <br>
    <br>
    Nous vous rappelons qu'il faut justifier et finaliser les fiches de frais avant qu'elles ne soient cloturées.
    <br>
    <br>
    Bonne journée,
    GSB.
</div>

<?php
require($repInclude . "_pied.inc.html");
require($repInclude . "_fin.inc.php");
?>
