<?php
// ***************************************************************************
// ***************************************************************************
// ***************************************************************************
// This file is part of OpenILLink software.
// Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2015, 2016, 2017 CHUV.
// Original author(s): Pablo Iriarte <pablo@iriarte.ch>
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
// Units table : List of all the units defined on the internal ILL network
// 

require ("config.php");
require ("authcookie.php");
require_once ("connexion.php");
if (!empty($_COOKIE['illinkid']))
{
if (($monaut == "admin")||($monaut == "sadmin"))
{
$myhtmltitle = $configname[$lang] . " : gestion des unités";
require ("headeradmin.php");
echo "\n";
// 
// Localizations List
// 
echo "<h1>Gestion des unités du réseau</h1>\n";
$req = "SELECT * FROM units ORDER BY name1 ASC, code ASC";// LIMIT 0, 200";
$result = dbquery($req);
$total_results = iimysqli_num_rows($result);
$nb = $total_results;

// Construction du tableau de resultats
echo "</center>\n";
echo "<b><br/>".$total_results;
if ($total_results == 1)
echo " unité trouvée</b></font>\n";
else
echo " unités trouvées</b></font>\n";
echo "<br/>";
echo "<br/>";

echo "<table id=\"one-column-emphasis\" summary=\"\">\n";
echo "<colgroup>\n";
echo "<col class=\"oce-first\" />\n";
echo "</colgroup>\n";
echo "\n";
echo "<thead>\n";
echo "<tr>\n";
echo "<th scope=\"col\">code</th>\n";
echo "<th scope=\"col\">".$guiLabelName1[$lang]."</th>\n";
// echo "<th scope=\"col\">name2</th>\n";
// echo "<th scope=\"col\">name3</th>\n";
// echo "<th scope=\"col\">name4</th>\n";
// echo "<th scope=\"col\">name5</th>\n";
echo "<th scope=\"col\">".$guiLibrary[$lang]."</th>\n";
echo "<th scope=\"col\">".$guiDepartment[$lang]."</th>\n";
echo "<th scope=\"col\">".$guiFaculty[$lang]."</th>\n";
echo "<th scope=\"col\">ip int.1</th>\n";
echo "<th scope=\"col\">ip int.2</th>\n";
echo "<th scope=\"col\">ip ext.</th>\n";
echo "<th scope=\"col\">".$guiNeedValidation[$lang]."</th>\n";
echo "<th scope=\"col\">".$guiEdit[$lang]."</th>\n";
echo "</tr>\n";
echo "</thead>\n";
echo "<tbody>\n";
for ($i=0 ; $i<$nb ; $i++)
{
$enreg = iimysqli_result_fetch_array($result);
$unitid = $enreg['id'];
$unitcode = $enreg['code'];
$unitname1 = $enreg['name1'];
$unitname2 = $enreg['name2'];
$unitname3 = $enreg['name3'];
$unitname4 = $enreg['name4'];
$unitname5 = $enreg['name5'];
$unitlibrary = $enreg['library'];
$unitdepartment = $enreg['department'];
$unitfaculty = $enreg['faculty'];
$unitip1 = $enreg['internalip1display'];
$unitip2 = $enreg['internalip2display'];
$unitipext = $enreg['externalipdisplay'];
$validation = $enreg['validation'];
echo "<tr>\n";
echo "<td><b>" . htmlspecialchars($unitcode) . "</b></td>\n";
echo "<td>".htmlspecialchars($unitname1)."</td>\n";
// echo "<td>".$unitname2."</td>\n";
// echo "<td>".$unitname3."</td>\n";
// echo "<td>".$unitname4."</td>\n";
// echo "<td>".$unitname5."</td>\n";
echo "<td>".htmlspecialchars($unitlibrary)."</td>\n";
echo "<td>".htmlspecialchars($unitdepartment)."</td>\n";
echo "<td>".htmlspecialchars($unitfaculty)."</td>\n";
echo "<td>".htmlspecialchars($unitip1)."</td>\n";
echo "<td>".htmlspecialchars($unitip2)."</td>\n";
echo "<td>".htmlspecialchars($unitipext)."</td>\n";
echo "<td>".htmlspecialchars($validation)."</td>\n";
if (($monaut == "admin")||($monaut == "sadmin"))
{
echo "<td><a href=\"edit.php?table=units&id=".htmlspecialchars($unitid)."\"><img src=\"img/edit.png\" title=\"Editer la fiche\" width=\"20\"></a></td>";
}
echo "</tr>\n";
}
echo "</tbody>\n";
echo "</table>\n";
echo "\n";
echo "<br/><br/><ul>\n";
echo "<b><a href=\"new.php?table=units\">Ajouter une nouvelle unité </a></b>\n";
echo "<br/><br/>\n";
echo "</ul>\n";
require ("footer.php");
}
else
{
require ("header.php");
echo "Vos droits sont insuffisants pour consulter cette page</b></font></center><br /><br /><br /><br />\n";
require ("footer.php");
}
}
else
{
require ("header.php");
require ("loginfail.php");
require ("footer.php");
}
?>
