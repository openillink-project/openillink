<?php


require ("includes/config.php");
require ("includes/authcookie.php");
require_once ("includes/toolkit.php");
if (!empty($_COOKIE[illinkid]))
{
  if (($monaut == "admin")||($monaut == "sadmin")||($monaut == "user"))
  {
    require ("includes/headeradmin.php");
    echo "\n";
    echo "<br/><br/>\n";

    $madate=date("Y-m-d");
    $beginDate = date("d.m.Y",mktime(0, 0, 0, 1, 1, date("Y")-1));
    $endDate = date("d.m.Y",mktime(0, 0, 0, 12, 31, date("Y")-1));

    // contenu ici
    echo "<h1>Rapports et statitstiques</h1>\n";
    echo "<center>";
    echo "<table>\n";
    echo "<form action=\"includes/report.php\" method=\"GET\">\n";
    echo "<tr> <td>Période du</td> <td><input name=\"datedu\" type=\"text\" size=\"10\" value=\"".$beginDate/*madate*/. "\" /> au <input name=\"dateau\" type=\"text\" size=\"10\" value=\"".$endDate/*madate*/. "\" /> </td> </tr>\n";
    echo "<tr> <td>Type du rapport</td> <td> <select name=\"type\"> <option value=\"liste_tout\">Listing total</> <option value=\"liste_service\">Listing par service</> <option value=\"resume_service\">Résumé par service</> <option value=\"stats\">Statistiques</>  </select> </td> </tr>\n";
    /*<option value=\"groupe_service\">Listing par service groupé par mail</>*/ // option désactivtée suite à discussion avec IK
    //echo "<tr> <td>Status</td> <td> <select name=\"stade\"> <option value=\"tout\">Reçues et envoyées + Invoice + Soldées</> <option value=\"recue_invoice\">Reçue et envoyée + Invoice</> <option value=\"recue_envoyee\">Reçues et envoyées</> <option value=\"invoice\">Invoice</> <option value=\"soldee\">Soldées</> </select> </td> </tr>\n";
    echo "<tr> <td>Format du rapport</td> <td> <select name=\"format\"> <option value=\"csv\">text/csv</> <option value=\"tab\">texte/tabulé</>  </select> </td> </tr>\n";
    echo "<tr><td /> <input type=\"hidden\" name=\"biblio\" value=\"". $monbib ."\" /> <td> <input type=\"submit\" value=\"générer\" /> </td></tr>\n";
    echo "</form>\n";
    echo "</table>\n";
    echo "</center>";

    echo "<h1>Quel option choisir?</h1>";
    echo boxContent('liste_tout', "LISTING TOTAL", "<div>Liste des commandes dont la date d’envoi est inclue dans la période indiquée lors de la génération du fichier ; la liste des résultats est triée par date d’envoi décroissante et reporte toutes les colonnes de la table.</div>".
    '<div>Liste des colonnes actuellement disponibles :<br/>'.
    'refinterbib, nom, prénom, mail, illinkid, date , envoye, prix, localisation, type_doc, titre_periodique, annee, numero, pages, titre_article, stade, uid, issn, eissn</div>'.
    '<div>Cette liste peut changer dynamiquement à l’ajout des nouvelles colonnes.</div>');

    echo boxContent('liste_service', "LISTING PAR SERVICE", 
    "<div>Le document généré avec cette option détaille les commandes avec statut « Reçue et envoyée au client », qui sont assignées à la bibliothèque à laquelle l’utilisateur qui génère la statistique est rattaché.</div>".
    "<div>Les commandes retenues sont uniquement celles pour qui la date de saisie ou la date d’envoi est comprise dans l’intervalle de dates indiqué au moment de la génération du document.</div>".
    "<div>Pour chaque commande les colonnes suivantes sont renseignées :<br/>".
    "refinterbib, nom, prénom, mail, illinkid, date, envoye, prix, localisation, type_doc, titre_periodique, annee, volume, numero, pages, titre_article, stade, uid, issn, eissn.</div>");

    echo boxContent('resume_service', "RÉSUMÉ PAR SERVICE", 
    "<div>Liste des commandes regroupées par service, sont détaillées:<ul><li>l’organisation (qui reste en principe vide à l’heure actuelle);</li><li>le service i.e. l’unité qui a fait la demande, désigné par son code;</li><li>le CGRA du service, i.e. l’unité qui a effectué la demande;</li><li>le nombre de commandes pour le service/CGRA;</li><li>le prix, correspondant au montant total facturé pour l’ensemble des commandes selon les données renseignées dans openillink.</li></ul>Uniquement les commandes avec statut « Reçue et envoyée au client » sont prises en compte.</div><div/>");

    echo boxContent('stats', "STATISTIQUES", 
    "Continent trois tableaux:<ul><li>commandes par statut (numéro total et en pourcentage);</li><li> commandes par localisation (numéro total et en pourcentage);</li><li> détail des commandes facturée par localisation (numéro total et en pourcentage)</li></ul>Uniquement les commandes avec statut soldé figurent dans cette statistique.<div/>");
    echo "</div></div>\n";
    require ("includes/footer.php");
  }
}
else
{
  require ("includes/header.php");
  require ("includes/loginfail.php");
  require ("includes/footer.php");
}

?>

