//Cache tout le contenu du site, si une des deux valeurs des listes déroulantes n'a pas changer
if ( document.getElementById('lstVisiteur').selectedIndex == '' || document.getElementById('lstMois').selectedIndex == '')
{
    div=document.getElementById('idDivEnreg');
    parent=div.parentNode;
    parent.removeChild(div);
}
    
    
//Cache le boutton valider, si la liste déroulante n'a pas changer
if (document.getElementById('lstEtat').selectedIndex == '')
{
    div=document.getElementById('idDivButton');
    parent=div.parentNode;
    parent.removeChild(div);
}
    
    
//Soumet le formulaire du visiteur choisi, si la valeur de la liste déroulante est modifier
function ChangerVisiteur()
{
	
    if ( document.getElementById('lstVisiteur').selectedIndex != 0 )
    {
        document.getElementById('formValidVisiteur').submit();
        
    }
    else
    {
        div=document.getElementById('idDivEnreg');
        parent=div.parentNode;
        parent.removeChild(div);
    }
}


//Soumet le formulaire du mois choisi, si la valeur de la liste déroulante est modifier
function ChangerMois()
{
    
    if ( document.getElementById('lstMois').selectedIndex != 0 )
    {
        document.getElementById('formValidMois').submit();
        
    }
    else
    {
        div=document.getElementById('idDivEnreg');
        parent=div.parentNode;
        parent.removeChild(div);
    }
}


//Soumet le formulaire de l'etat choisi, si la valeur de la liste déroulante est modifier
function ChangerEtat()
{
    
    if ( document.getElementById('lstEtat').selectedIndex != 0 )
    {
        document.getElementById('FrmEtat').submit();
        
    }
    else
    {
        div=document.getElementById('idDivButton');
        parent=div.parentNode;
        parent.removeChild(div);
    }
}


//Modifie la ligne de hors forfait en envoyer les valeurs dans l'url
function ModifLigneHF(idLigneHF){
    //alert(document.getElementById('idLigneHF').value);
    document.location.replace('http://'+ location.hostname + location.pathname + '?etape=validerModificationLigneHF&idLigneHF='+ idLigneHF + '&dateHF=' + document.getElementById('dateHF'+idLigneHF).value + '&libelleHF=' + document.getElementById('libelleHF'+idLigneHF).value  + '&montantHF=' + document.getElementById('montantHF'+idLigneHF).value);
}


//Modifie l'etat de la fiche de frais en envoyant les valeurs dans l'url
function ModifEtat(visiteur, mois, etat)
{
    document.location.replace('http://'+ location.hostname + location.pathname + '?etape=validerModificationEtat&idvisiteur=' + visiteur + '&moisvisiteur=' + mois + '&etat=' + etat);
}
