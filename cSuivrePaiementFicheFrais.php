<?php
/**
 * Page d'accueil de l'application web AppliFrais
 * @package default
 * @todo  RAS
 */
$repInclude = './include/';
require($repInclude . "_init.inc.php");

// page inaccessible si comptable non connect�
if (!estConnecte()) {
    header("Location: cSeConnecterComptable.php");
}
require($repInclude . "_entete.inc.html");
require($repInclude . "_sommaireComptable.inc.php");
$message="";
$etape=lireDonnee("etape", "");
$unIdVisiteur=lireDonnee("unIdVisiteur", "");
$unMois=lireDonnee("unMois", "");
$nbJustificatifs=lireDonnee("nbJustificatifs","");
if ($etape == "validerMisePaiement")
{
    modifierEtatFicheFrais($idConnexion, $unMois, $unIdVisiteur, 'MP', $nbJustificatifs);
    modifierEtatFicheFrais($idConnexion, $unMois, $unIdVisiteur, 'RB', $nbJustificatifs);
}
?>

<html>
    <body>
        <div id="idDivEnreg">
            <div id="contenu">
                <form action="cSuivrePaiementFicheFrais.php" method="post">
                    <div class="corpsForm">
                        <table class="listeLegere">
                            <caption>Descriptif des montants totaux à rembourser
                            </caption>
                            <tr>
                                <th class="idvisiteur">Numero du visteur</th>
                                <th class="mois">Mois</th>
                                <th class="nbjustificatifs">Nombre de justificatifs</th>  
                                <th class="montantforfait">Montant total</th>
                            </tr>
                            <?php
                            // demande de la requête pour obtenir la liste des éléments hors
                            // forfait du visiteur connecté pour le mois demandé
                            $reqFicheFrais = obtenirDonneesFicheFrais();
                            $idJeuFicheFrais = mysql_query($reqFicheFrais, $idConnexion);
                            $lgFicheFrais = mysql_fetch_assoc($idJeuFicheFrais);
                            if (mysql_num_rows($idJeuFicheFrais)==false)
                            {
                               $message='Il n y a pas de fiche de frais a mettre en paiement ou a remboursé';
                            }
                            // parcours des frais hors forfait du visiteur connecté
                            while (is_array($lgFicheFrais)) {
                                ?>
                                <tr>
                                    <td><?php echo $lgFicheFrais["idVisiteur"]; ?></td>
                                    <td><?php echo $lgFicheFrais["mois"]; ?></td>
                                    <td><?php echo $lgFicheFrais["nbJustificatifs"]; ?></td>
                                    <td><?php echo $lgFicheFrais["montantValide"]; ?></td>
                                    <td><a href="?etape=validerMisePaiement&amp;unIdVisiteur=<?php echo $lgFicheFrais["idVisiteur"]; ?>&amp;unMois=<?php echo $lgFicheFrais["mois"]; ?>&amp;nbJustificatifs=<?php echo $lgFicheFrais["nbJustificatifs"]; ?>"
                               onclick="return confirm('Voulez-vous vraiment valider la fiche de frais ?');"
                               title="Valider la fiche de frais">Valider</a></td>
                                </tr>
                                <?php
                                $lgFicheFrais = mysql_fetch_assoc($idJeuFicheFrais);
                            }
                            mysql_free_result($idJeuFicheFrais);
                            ?>
                        </table>
                        <p class="erreur"><?php echo $message; ?></p>
                    </div>
                </form>

            </div>
        </div>
    </body>
</html>

<?php
require($repInclude . "_pied.inc.html");
require($repInclude . "_fin.inc.php");
?>