<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2007, 2008, 2009, 2010, 2011, 2012, 2013 UNIGE.
// Copyright (C) 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017 CHUV.
// Original author(s): Jan Krause <pro@jankrause.net>
// Other contributors are listed in the AUTHORS file at the top-level
// directory of this distribution.
// 
// OpenILLink is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// 
// OpenILLink is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with OpenILLink.  If not, see <http://www.gnu.org/licenses/>.
// 
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
//

require ("includes/config.php");
require ("includes/authcookie.php");
require_once ("includes/toolkit.php");
if (!empty($_COOKIE['illinkid']))
{
  if (($monaut == "admin")||($monaut == "sadmin")||($monaut == "user"))
  {
   if (empty($_GET['do_report'])) {
	// display form to setup report parameters
    require ("includes/headeradmin.php");
    echo "\n";
    echo "<br/><br/>\n";

    $madate=date("Y-m-d");
    $beginDate = date("d.m.Y",mktime(0, 0, 0, 1, 1, date("Y")-1));
    $endDate = date("d.m.Y",mktime(0, 0, 0, 12, 31, date("Y")-1));

    // contenu ici
    echo "<h1>".__("Reports and statistics")."</h1>\n";
    echo "<center>";
    echo "<table>\n";
    echo "<form action=\"reports.php\" method=\"GET\">\n";
    echo "<tr> <td>".__("Period from")."</td> <td><input name=\"datedu\" type=\"text\" size=\"10\" value=\"".$beginDate/*madate*/. "\" /> ".__("to the")." <input name=\"dateau\" type=\"text\" size=\"10\" value=\"".$endDate/*madate*/. "\" /> </td> </tr>\n";
    echo "<tr> <td>".__("Type of report")."</td> <td> <select name=\"type\"> <option value=\"liste_tout\">".__("Total listing")."</> <option value=\"liste_service\">".__("Listing by service")."</> <option value=\"resume_service\">".__("Summary by service")."</> <option value=\"stats\">".__("Statistics")."</>  </select> </td> </tr>\n";
    /*<option value=\"groupe_service\">Listing par service groupé par mail</>*/ // option désactivtée suite à discussion avec IK
    //echo "<tr> <td>Status</td> <td> <select name=\"stade\"> <option value=\"tout\">Reçues et envoyées + Invoice + Soldées</> <option value=\"recue_invoice\">Reçue et envoyée + Invoice</> <option value=\"recue_envoyee\">Reçues et envoyées</> <option value=\"invoice\">Invoice</> <option value=\"soldee\">Soldées</> </select> </td> </tr>\n";
    echo "<tr> <td>".__("Report Format")."</td> <td> <select name=\"format\"> <option value=\"csv\">".__("text/csv")."</> <option value=\"tab\">".__("text/tab")."</>  </select> </td> </tr>\n";
    echo "<tr><td /> <input type=\"hidden\" name=\"biblio\" value=\"". htmlspecialchars($monbib) ."\" /> <td> <input type=\"submit\" name=\"do_report\" value=\"".__("Generate")."\" /> </td></tr>\n";
    echo "</form>\n";
    echo "</table>\n";
    echo "</center>";

    echo "<h1>".__("Which option to choose?")."</h1>";
    echo boxContent('liste_tout', "LISTING TOTAL", "<div>".__("List of orders whose sending date is included in the period indicated when the file was generated; The result list is sorted by decreasing send date and reports all columns in the table.")."</div>".
    '<div>'.__("List of currently available columns:").'<br/>'.
    'refinterbib, nom, prénom, mail, illinkid, date , envoye, prix, localisation, type_doc, titre_periodique, annee, numero, pages, titre_article, stade, uid, issn, eissn</div>'.
    '<div>'.__("This list can change dynamically when new columns are added.").'</div>');

    echo boxContent('liste_service', "LISTING PAR SERVICE", 
    "<div>".__("The document generated with this option details the orders with the status 'Received and sent to the client', which are assigned to the library to the user who generates the statistic is attached.")."</div>".
    "<div>".__("The selected orders are only those for which the date of entry or the date of sending is within the range of dates indicated at the time of the generation of the document.")."</div>".
    "<div>".__("For each order, the following columns are filled in:")."<br/>".
    "refinterbib, nom, prénom, mail, illinkid, date, envoye, prix, localisation, type_doc, titre_periodique, annee, volume, numero, pages, titre_article, stade, uid, issn, eissn.</div>");

    echo boxContent('resume_service', "RÉSUMÉ PAR SERVICE", 
    "<div>".__("List of orders grouped by service, are detailed:")."<ul><li>".__("The organization (which in principle is empty at the moment);")."</li><li>".__("The service i.e. the unit that made the request, designated by its code;")."</li><li>".__("The CGRA of the service, i.e. the unit that made the request;")."</li><li>".__("The number of orders for the service / CGRA;")."</li><li>".__("The price, corresponding to the total amount invoiced for all orders according to the data entered in openillink.")."</li></ul>".__("Only orders with status 'Received and sent to the customer' are taken into account.")."</div><div/>");

    echo boxContent('stats', "STATISTIQUES", __("Contains three tables:").
    "<ul><li>".__("Orders by status (total number and percentage);")."</li><li>".__("Orders by location (total number and percentage);")."</li><li>".__("Order detail invoiced by location (total number and percentage)")."</li></ul>".__("Only sales-ordered orders are included in this statistic.")."<div/>");
    echo "</div></div>\n";
    require ("includes/footer.php");
	
	} else {
		// output the report
		require ("includes/report.php");
		$datedu = ((!empty($_GET['datedu'])) && isValidInput($_GET['datedu'],10,'s',false)) ? $_GET['datedu'] : NULL;
		$dateau = ((!empty($_GET['dateau'])) && isValidInput($_GET['dateau'],10,'s',false)) ? $_GET['dateau'] : NULL;
		$type = ((!empty($_GET['type'])) && isValidInput($_GET['type'],25,'s',false)) ? $_GET['type'] : NULL;
		$format = ((!empty($_GET['format'])) && isValidInput($_GET['format'],3,'s',false)) ? $_GET['format'] : NULL;
		$stade = NULL;
		do_report($datedu, $dateau, $type, $format, $stade, $monbib);
	}
  }
}
else
{
  require ("includes/header.php");
  require ("includes/loginfail.php");
  require ("includes/footer.php");
}

?>

