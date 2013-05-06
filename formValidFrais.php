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

$messageModif = "";
$message="";
$VisiteurChoisi = lireDonnee("lstVisiteur", "");
$MoisChoisi = lireDonnee("lstMois", "");
$etape = lireDonnee("etape", "demanderSaisie");
$idLigneHF = lireDonnee("idLigneHF", "");
$dateHF = lireDonnee("dateHF", "");
$libelleHF = lireDonnee("libelleHF", "");
$montantHF = lireDonnee("montantHF", "");
$nbJustificatifs = lireDonnee("nbJustificatifs", "");
// acquisition des quantités des éléments forfaitisés
$tabQteEltsForfait = lireDonneePost("txtEltsForfait", "");
// structure de décision sur les différentes étapes du cas d'utilisation
if ($etape == "validerSaisie") {
// l'utilisateur valide les éléments forfaitisés         
// vérification des quantités des éléments forfaitisés
    $ok = verifierEntiersPositifs($tabQteEltsForfait);
    if (!$ok) {
        ajouterErreur($tabErreurs, "Chaque quantité doit être renseignée et numérique positive.");
    } else { // mise à jour des quantités des éléments forfaitisés
        modifierEltsForfait($idConnexion, $MoisChoisi, $VisiteurChoisi, $tabQteEltsForfait);
        $messageModif = "Les montant ont bien était modifiés";
    }
}
//si c'est vrai, on active la fonction supprimerLigneHF
elseif ($etape == "validerRefusLigneHF") {
    if (preg_match("[Refus]", $libelleHF)) {
        ModifierLigneHF($idConnexion, $idLigneHF, $dateHF, $libelleHF, $montantHF);
    } else {
        $libelleHF = "[Refus]" . $libelleHF;
        ModifierLigneHF($idConnexion, $idLigneHF, $dateHF, $libelleHF, $montantHF);
    }
}
//si c'est vrai, on active la fonction ModifierLigneHF
elseif ($etape == "validerModificationLigneHF") {
    ModifierLigneHF($idConnexion, $idLigneHF, $dateHF, $libelleHF, $montantHF);
} elseif ($etape == "ValiderEtat") {
    modifierEtatFicheFrais($idConnexion, $MoisChoisi, $VisiteurChoisi, 'VA', $nbJustificatifs);
    calculerMontantTotal($VisiteurChoisi, $MoisChoisi);
} else {
// on ne fait rien, étape non prévue 
}

$requeteVisiteur = ("SELECT distinct nom,id FROM visiteur inner join fichefrais on visiteur.id = fichefrais.idVisiteur where idEtat='CL'");
$idJeuVisiteur = mysql_query($requeteVisiteur, $idConnexion);
if (mysql_num_rows($idJeuVisiteur) == false) {
    $message = 'Toutes les fiches de frais sont validées';
}
?>
<p class="erreur"><?php echo $message; ?></p>
<title>Validation des frais de visite</title>

<!-- Début de la liste déroulante affichant les visiteurs ayant une fiche de frais -->
<form id="formValidVisiteur" method="post" action="formValidFrais.php" >
    <h1> Validation des frais par visiteur </h1>
    <label class="titre">Choisir le visiteur :</label>
    <select name="lstVisiteur" id="lstVisiteur" onChange="ChangerVisiteur();">
        <option value="0" hidden="hidden"> Choisir un visiteur</option>

        <?php
        while ($lgVisiteur = mysql_fetch_array($idJeuVisiteur)) {
            ?>
            <option value="<?php echo $lgVisiteur['id']; ?>"<?php if ($lgVisiteur['id'] == $VisiteurChoisi) { ?>  selected="selected"<?php } ?> > <?php echo $lgVisiteur['nom']; ?>
            </option>
            <?php
        }
        mysql_free_result($idJeuVisiteur);
        ?>
    </select>
</form>
<!-- Fin de la liste déroulante affichant les visiteurs ayant une fiche de frais -->



<!-- Début de la liste déroulante affichant les mois du visiteur séléctionner -->
<form id="formValidMois" method="post" action="formValidFrais.php">
    <label class="titre">Choisir le mois :</label>
    <input type="hidden" name="lstVisiteur" value="<?php echo $VisiteurChoisi ?>"/>
    <select name="lstMois" id="lstMois" onChange="ChangerMois();">
        <option value="0" hidden="hidden"> Choisir un mois</option>
        <?php
        $requeteMois = ("SELECT distinct mois, idVisiteur FROM fichefrais where idVisiteur = '" . $VisiteurChoisi . "' and idEtat='CL' ");
        $idJeuMois = mysql_query($requeteMois, $idConnexion);
        while ($lgMois = mysql_fetch_array($idJeuMois)) {
            ?>
            <option value="<?php echo $lgMois['mois']; ?>"<?php if ($lgMois['mois'] == $MoisChoisi) { ?> selected="selected"<?php } ?>><?php echo $lgMois['mois']; ?></option>			
            <?php
        }
        mysql_free_result($idJeuMois);
        ?>
    </select>
</form>
<!-- Fin de la liste déroulante affichant les mois du visiteur séléctionner -->

<div id="idDivEnreg">
    <div id="contenu">


        <form action="" method="post">
            <div class="corpsForm">
                <input type="hidden" name="etape" value="validerSaisie" />
                <fieldset>
                    <legend>Eléments forfaitisés
                    </legend>
                    <p class="info"><?php echo $messageModif; ?></p>
                    <input type="hidden" name="lstVisiteur" value="<?php echo $VisiteurChoisi ?>"/>
                    <input type="hidden" name="lstMois" value="<?php echo $MoisChoisi ?>"/>
                    <?php
// demande de la requête pour obtenir la liste des éléments 
// forfaitisés du visiteur connecté pour le mois demandé
                    $req = obtenirReqEltsForfaitFicheFrais($MoisChoisi, $VisiteurChoisi);
                    $idJeuEltsFraisForfait = mysql_query($req, $idConnexion);
                    echo mysql_error($idConnexion);
                    $lgEltForfait = mysql_fetch_assoc($idJeuEltsFraisForfait);
                    while (is_array($lgEltForfait)) {
                        $idFraisForfait = $lgEltForfait["idFraisForfait"];
                        $libelle = $lgEltForfait["libelle"];
                        $quantite = $lgEltForfait["quantite"];
                        ?>
                        <p>
                            <label for="<?php echo $idFraisForfait ?>"> <?php echo $libelle; ?> : </label>
                            <input type="text" id="<?php echo $idFraisForfait ?>" 
                                   name="txtEltsForfait[<?php echo $idFraisForfait ?>]" 
                                   size="10" maxlength="5"
                                   title="Entrez la quantité de l'élément forfaitisé" 
                                   value="<?php echo $quantite; ?>" />
                        </p>
                        <?php
                        $lgEltForfait = mysql_fetch_assoc($idJeuEltsFraisForfait);
                    }
                    mysql_free_result($idJeuEltsFraisForfait);
                    ?>
                </fieldset>
            </div>
            <div class="piedForm">
                <p>
                    <input id="ok" type="submit" value="Modifier ligne frais forfait" size="20" 
                           title="Enregistrer les nouvelles valeurs des éléments forfaitisés" />

                </p> 

            </div>

        </form>


        <form name = "FrmAffichage" method = "post" action = "formValidFrais.php">
            <p class = "titre" /><h2>Hors Forfait</h2>
            <input type = "hidden" name = "lstVisiteur" value = "<?php echo $VisiteurChoisi ?>"/>
            <input type = "hidden" name = "lstMois" value = "<?php echo $MoisChoisi ?>"/>
            <table class = "listeLegere">
                <tr>
                    <th class = "date">Date</th>
                    <th class = "libelle">Libellé</th>
                    <th class = "montant">Montant</th>
                    <th class = "Supprimer">Refuser</th>
                    <th class = "Modifier">Modifier</th>
                </tr>
                <?php
                // demande de la requête pour obtenir la liste des éléments hors
                // forfait du visiteur connecté pour le mois demandé
                $req = obtenirReqEltsHorsForfaitFicheFrais($MoisChoisi, $VisiteurChoisi);
                $idJeuEltsHorsForfait = mysql_query($req, $idConnexion);
                $lgEltHorsForfait = mysql_fetch_assoc($idJeuEltsHorsForfait);
                // parcours des éléments hors forfait
                while (is_array($lgEltHorsForfait)) {
                    ?>
                    <input type="hidden" id="idLigneHF<?php echo $lgEltHorsForfait["id"]; ?>" value="<?php echo $lgEltHorsForfait["id"]; ?>" />
                    <tr>
                        <td><input type="text" size="10" name="dateHF" id="dateHF<?php echo $lgEltHorsForfait["id"]; ?>" value="<?php echo $lgEltHorsForfait["date"]; ?>"/></td>
                        <td><input type="text" name="libelleHF" id="libelleHF<?php echo $lgEltHorsForfait["id"]; ?>" value="<?php echo filtrerChainePourNavig($lgEltHorsForfait["libelle"]); ?>"/></td>
                        <td><input type="text" size="5" name="montantHF" id="montantHF<?php echo $lgEltHorsForfait["id"]; ?>" value="<?php echo $lgEltHorsForfait["montant"]; ?>"/></td>
                        <td><a href="?etape=validerRefusLigneHF&amp;idLigneHF=<?php echo $lgEltHorsForfait["id"]; ?>&amp;dateHF=<?php echo $lgEltHorsForfait["date"]; ?>&amp;libelleHF=<?php echo $lgEltHorsForfait["libelle"]; ?>&amp;montantHF=<?php echo $lgEltHorsForfait["montant"]; ?>"
                               onclick="return confirm('Voulez-vous vraiment refuser cette ligne de frais hors forfait ?');"
                               title="Supprimer la ligne de frais hors forfait">Refuser</a></td>
                        <td><a onclick="ModifLigneHF(<?php echo $lgEltHorsForfait["id"]; ?>);"
                               title="Modifier la ligne de frais hors forfait">Modifier</a></td>
                    </tr>
                    <?php
                    $lgEltHorsForfait = mysql_fetch_assoc($idJeuEltsHorsForfait);
                }
                mysql_free_result($idJeuEltsHorsForfait);
                ?>
            </table>
            <p class="titre"></p>
            Nb Justificatifs<input type="text" class="zone" size="4" name="nbJustificatifs" required/>
            <p class="titre"/><label class="titre">&nbsp;</label>

            <input name="etape" type="submit" value="ValiderEtat" size="20" class="zone" />
        </form>
        </>
    </div>
    
    <script language="Javascript">
<?php require($repInclude . "ListeDeroulante.js"); ?>
    </script>


    <?php
    require($repInclude . "_pied.inc.html");
    require($repInclude . "_fin.inc.php");
    ?>