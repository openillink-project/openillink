<?php


require ("includes/config.php");
require ("includes/authcookie.php");
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

